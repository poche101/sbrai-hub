import { apiFetch } from './api';

// Complements the curated static dictionary in i18n.js (which stays
// authoritative for elements tagged data-i18n — accurate, zero-cost,
// ported from the real mobile app strings). This module handles
// EVERYTHING ELSE on the page via the backend's real /translate/batch
// endpoint (Google Translate), so "switch language" actually covers the
// whole site, not just the dozen curated nav/menu strings.

const CACHE_PREFIX = 'sbrai_mt:';
const CHUNK_SIZE = 50; // backend validates texts.* max:50 per call
const MAX_TEXT_LENGTH = 1000; // backend validates texts.* max:1000 chars each

const originalText = new WeakMap(); // text node -> its original English
const originalPlaceholder = new WeakMap(); // element -> its original placeholder

function hash(str) {
    let h = 0;
    for (let i = 0; i < str.length; i++) {
        h = (h * 31 + str.charCodeAt(i)) >>> 0;
    }
    return h.toString(36);
}

function cacheGet(lang, text) {
    try {
        return localStorage.getItem(CACHE_PREFIX + lang + ':' + hash(text));
    } catch (e) {
        return null;
    }
}

function cacheSet(lang, text, translated) {
    try {
        localStorage.setItem(CACHE_PREFIX + lang + ':' + hash(text), translated);
    } catch (e) {
        // localStorage full/disabled — translation still works, just re-fetches next time.
    }
}

function collectTextNodes(root) {
    const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, {
        acceptNode(node) {
            const text = node.nodeValue.trim();
            if (!text) return NodeFilter.FILTER_REJECT;
            const parent = node.parentElement;
            if (!parent) return NodeFilter.FILTER_REJECT;
            // Skip non-visible/non-text-content elements, opt-outs, editable
            // fields (never overwrite what a user is typing), and anything
            // already handled by the curated static dictionary.
            if (parent.closest('script, style, noscript, [data-no-translate], [data-i18n], input, textarea')) {
                return NodeFilter.FILTER_REJECT;
            }
            return NodeFilter.FILTER_ACCEPT;
        },
    });
    const nodes = [];
    let n;
    while ((n = walker.nextNode())) nodes.push(n);
    return nodes;
}

function collectPlaceholders(root) {
    return Array.from(root.querySelectorAll('input[placeholder], textarea[placeholder]')).filter(
        (el) => !el.closest('[data-no-translate]') && !el.hasAttribute('data-i18n-placeholder')
    );
}

async function translateBatchRemote(texts, target) {
    const out = [];
    for (let i = 0; i < texts.length; i += CHUNK_SIZE) {
        const chunk = texts.slice(i, i + CHUNK_SIZE).map((t) => t.slice(0, MAX_TEXT_LENGTH));
        try {
            const data = await apiFetch('/translate/batch', { method: 'POST', json: { texts: chunk, target } });
            out.push(...(data.translations || chunk));
        } catch (e) {
            out.push(...chunk); // graceful fallback — original text stays visible
        }
    }
    return out;
}

export async function translatePage(target) {
    const root = document.body;

    if (target === 'en') {
        // Instant and free — restore from memory, no API call needed.
        collectTextNodes(root).forEach((node) => {
            if (originalText.has(node)) node.nodeValue = originalText.get(node);
        });
        collectPlaceholders(root).forEach((el) => {
            if (originalPlaceholder.has(el)) el.placeholder = originalPlaceholder.get(el);
        });
        return;
    }

    const textNodes = collectTextNodes(root);
    const placeholderEls = collectPlaceholders(root);

    textNodes.forEach((node) => {
        if (!originalText.has(node)) originalText.set(node, node.nodeValue);
    });
    placeholderEls.forEach((el) => {
        if (!originalPlaceholder.has(el)) originalPlaceholder.set(el, el.placeholder);
    });

    const toFetch = [];
    const toFetchRefs = [];

    textNodes.forEach((node) => {
        const original = originalText.get(node);
        const cached = cacheGet(target, original);
        if (cached) {
            node.nodeValue = cached;
        } else {
            toFetch.push(original);
            toFetchRefs.push({ kind: 'node', ref: node, original });
        }
    });

    placeholderEls.forEach((el) => {
        const original = originalPlaceholder.get(el);
        const cached = cacheGet(target, original);
        if (cached) {
            el.placeholder = cached;
        } else {
            toFetch.push(original);
            toFetchRefs.push({ kind: 'placeholder', ref: el, original });
        }
    });

    if (!toFetch.length) return;

    const translations = await translateBatchRemote(toFetch, target);

    toFetchRefs.forEach((item, i) => {
        const translated = translations[i] || item.original;
        cacheSet(target, item.original, translated);
        if (item.kind === 'node') item.ref.nodeValue = translated;
        else item.ref.placeholder = translated;
    });
}

let currentTarget = 'en';
let debounceTimer = null;

function startObserving() {
    // childList/subtree only — deliberately NOT observing characterData or
    // attributes, since that's exactly what our own translatePage() writes
    // change. Watching only new-node insertion means our own translation
    // writes never re-trigger this observer, so there's no feedback loop —
    // it only fires for genuinely new content (e.g. browse.js injecting
    // listing cards after a fetch).
    const observer = new MutationObserver(() => {
        if (currentTarget === 'en') return;
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => translatePage(currentTarget), 400);
    });
    observer.observe(document.body, { childList: true, subtree: true });
}

export function initDynamicTranslate() {
    startObserving();

    window.addEventListener('sbrai:language-changed', (e) => {
        currentTarget = e.detail.code;
        translatePage(currentTarget);
    });

    const saved = localStorage.getItem('sbrai_language');
    if (saved && saved !== 'en') {
        currentTarget = saved;
        translatePage(saved);
    }
}
