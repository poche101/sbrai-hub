// Ported verbatim from the Flutter app's lib/core/config/app_strings.dart
// so wording matches the mobile app exactly rather than being retranslated.
// This covers fixed UI chrome (nav, buttons, headings) — NOT dynamic
// vendor-authored listing content, which would need the backend's
// /translate endpoints instead (not yet wired anywhere, mobile or web).

const LANGUAGES = [
    { code: 'en', label: 'English' },
    { code: 'ig', label: 'Igbo (Asụsụ Igbo)' },
    { code: 'yo', label: 'Yorùbá (Èdè Yorùbá)' },
    { code: 'ha', label: 'Hausa (Harshen Hausa)' },
    { code: 'fr', label: 'Français' },
];

const TABLE = {
    home: { en: 'Home', ig: 'Ụlọ', yo: 'Ilé', ha: 'Gida', fr: 'Accueil' },
    chat: { en: 'Chat', ig: 'Kwuo okwu', yo: 'Ìfọ̀rọ̀wánilẹ́nuwò', ha: 'Hira', fr: 'Discuter' },
    messages: { en: 'Messages', ig: 'Ozi', yo: 'Àwọn Ìránṣẹ́', ha: 'Saƙonni', fr: 'Messages' },
    post: { en: 'Post', ig: 'Zipu', yo: 'Fiwé', ha: 'Tura', fr: 'Publier' },
    dashboard: { en: 'Dashboard', ig: 'Bọọdụ', yo: 'Pátákó', ha: 'Allo', fr: 'Tableau de bord' },
    settings: { en: 'Settings', ig: 'Ntọala', yo: 'Ètò', ha: 'Saituna', fr: 'Réglages' },
    notifications: { en: 'Notifications' },
    favorites: { en: 'Favorites', ig: 'Ihe ọ masịrị gị', yo: 'Ayanfẹ', ha: 'Abubuwan so', fr: 'Favoris' },
    profile: { en: 'Profile', ig: 'Profaịlụ', yo: 'Àkọsílẹ̀', ha: 'Bayanan martaba', fr: 'Profil' },
    kyc: { en: 'KYC', ig: 'KYC', yo: 'KYC', ha: 'KYC', fr: 'KYC' },
    logout: { en: 'Logout', ig: 'Pụọ', yo: 'Jáde', ha: 'Fita', fr: 'Déconnexion' },
    subscription: { en: 'Subscription', ig: 'Ndenye aha', yo: 'Ìforúkọsílẹ̀', ha: 'Biyan kuɗi', fr: 'Abonnement' },
    searchPlaceholder: { en: 'What are you looking for?', ig: 'Kedu ihe ị na-achọ?', yo: 'Kín ni o ń wá?', ha: 'Me kake nema?', fr: 'Que recherchez-vous ?' },
    recommended: { en: 'Recommended for You', ig: 'Atụrụ Aro Maka Gị', yo: 'Ohun tí a dámọ̀ràn fún ọ', ha: 'Shawarwari a gare ka', fr: 'Recommandé pour vous' },
    items: { en: 'items', ig: 'ihe', yo: 'ohun', ha: 'abubuwa', fr: 'articles' },
    call: { en: 'Call', ig: 'Kpọọ', yo: 'Pè', ha: 'Kira', fr: 'Appeler' },
    allNigeria: { en: 'All Nigeria', ig: 'Naịjirịa Niile', yo: 'Gbogbo Nàìjíríà', ha: 'Duk Najeriya', fr: 'Tout le Nigeria' },
    trending: { en: 'Trending', ig: 'Na-ese', yo: 'Gbajúmọ̀', ha: 'Abin da ke tafiya', fr: 'Tendance' },
    buyer: { en: 'Buyer', ig: 'Onye Azụta', yo: 'Olùra', ha: 'Mai saye', fr: 'Acheteur' },
    vendor: { en: 'Vendor', ig: 'Onye Na-ere', yo: 'Olùtajà', ha: 'Mai sayarwa', fr: 'Vendeur' },

    cat_sharp_sand: { en: 'Sharp Sand', ig: 'Ájá Nkọ', yo: 'Iyanrin', ha: 'Yashi', fr: 'Sable' },
    cat_granite: { en: 'Granite', ig: 'Nkume Granite', yo: 'Òkúta Granite', ha: 'Dutsen Granite', fr: 'Granit' },
    cat_blocks: { en: 'Blocks', ig: 'Blọk', yo: 'Bíríkì', ha: 'Bulo', fr: 'Blocs' },
    cat_cement: { en: 'Cement', ig: 'Simenti', yo: 'Simenti', ha: 'Siminti', fr: 'Ciment' },
    cat_iron_rods: { en: 'Iron Rods', ig: 'Mkpịsị Ígwè', yo: 'Ọ̀pá Irin', ha: 'Sandunan ƙarfe', fr: 'Barres de fer' },
    cat_paints: { en: 'Paints', ig: 'Agba', yo: 'Àwọ̀', ha: 'Fenti', fr: 'Peintures' },
    cat_furniture: { en: 'Furniture', ig: 'Ngwá Ụlọ', yo: 'Ohun Èlò Ilé', ha: 'Kayan daki', fr: 'Meubles' },
    cat_scaffolding: { en: 'Scaffolding', ig: 'Ngwá Ọrụ', yo: 'Àtẹ̀gùn Ìkọ́lé', ha: 'Kayan hawa', fr: 'Échafaudage' },
    cat_logistics: { en: 'Logistics', ig: 'Ụgbọ Ibu', yo: 'Ìrìnnà Ẹrù', ha: 'Sufuri', fr: 'Logistique' },
    cat_borehole: { en: 'Borehole', ig: 'Olulu Mmiri', yo: 'Kànga', ha: 'Rijiya', fr: 'Forage' },
    cat_cleaning: { en: 'Cleaning', ig: 'Ihicha', yo: 'Ìṣọ́nà', ha: 'Tsaftacewa', fr: 'Nettoyage' },
    cat_fumigation: { en: 'Fumigation', ig: 'Ịgba Ọgwụ Ahụhụ', yo: 'Ìtọ́jú Kòkòrò', ha: 'Feshin ƙwari', fr: 'Fumigation' },
    cat_apartments: { en: 'Apartments', ig: 'Ụlọ Mgbaghari', yo: 'Ìyẹ̀wù Ilé', ha: 'Gidaje', fr: 'Appartements' },
    cat_houses: { en: 'Houses', ig: 'Ụlọ', yo: 'Ilé', ha: 'Gidaje', fr: 'Maisons' },
    cat_commercial: { en: 'Commercial', ig: 'Azụmahịa', yo: 'Ti Òwò', ha: 'Kasuwanci', fr: 'Commercial' },
    cat_land: { en: 'Land', ig: 'Ala', yo: 'Ilẹ̀', ha: 'Ƙasa', fr: 'Terrain' },

    welcomeBack: { en: 'Welcome Back', ig: 'Nnọọ Ọzọ', yo: 'Kaabọ Padà', ha: 'Barka da dawowa', fr: 'Content de vous revoir' },
    signInSubtitle: { en: 'Sign in to your account to continue', ig: "Banye n'akaụntụ gị ka ị gaa n'ihu", yo: 'Wọlé sí àkáǹtì rẹ láti tẹ̀síwájú', ha: 'Shiga asusun ka don ci gaba', fr: 'Connectez-vous pour continuer' },
    emailAddress: { en: 'Email Address', ig: 'Adreesị Email', yo: 'Àdírẹ́sì Ímeèlì', ha: 'Adireshin Imel', fr: 'Adresse e-mail' },
    password: { en: 'Password', ig: 'Okwuntughe', yo: 'Ọ̀rọ̀ Ìgbaniwọlé', ha: 'Kalmar sirri', fr: 'Mot de passe' },
    forgotPassword: { en: 'Forgot Password?', ig: 'Chefuru Okwuntughe?', yo: 'Gbàgbé Ọ̀rọ̀ Ìgbaniwọlé?', ha: 'Manta kalmar sirri?', fr: 'Mot de passe oublié ?' },
    signIn: { en: 'Sign In', ig: 'Banye', yo: 'Wọlé', ha: 'Shiga', fr: 'Se connecter' },
    noAccount: { en: "Don't have an account?", ig: 'Ọ nweghị akaụntụ?', yo: 'Ò ní àkáǹtì?', ha: 'Ba ka da asusu?', fr: "Vous n'avez pas de compte ?" },
    signUp: { en: 'Sign Up', ig: 'Debanye Aha', yo: 'Forúkọsílẹ̀', ha: 'Yi rajista', fr: "S'inscrire" },
    continueWithGoogle: { en: 'Continue with Google', ig: "Jiri Google Gaa N'ihu", yo: 'Tẹ̀síwájú pẹ̀lú Google', ha: 'Ci gaba da Google', fr: 'Continuer avec Google' },
    continueWithFacebook: { en: 'Continue with Facebook', ig: "Jiri Facebook Gaa N'ihu", yo: 'Tẹ̀síwájú pẹ̀lú Facebook', ha: 'Ci gaba da Facebook', fr: 'Continuer avec Facebook' },
    alreadyHaveAccount: { en: 'Already have an account?', ig: 'Ị nweelarị akaụntụ?', yo: 'Ṣé o ti ní àkáǹtì?', ha: 'Kana da asusu tuni?', fr: 'Vous avez déjà un compte ?' },

    myFavorites: { en: 'My Favorites', ig: "Ihe M Hụrụ N'anya", yo: 'Àwọn Ayanfẹ́ Mi', ha: 'Abubuwan da nake so', fr: 'Mes favoris' },
    noFavoritesYet: { en: 'No Favorites Yet', ig: "Ọ Nwebeghị Ihe Ị Hụrụ N'anya", yo: 'Kò Sí Ayanfẹ́ Síbẹ̀', ha: 'Babu abubuwan so tukuna', fr: 'Aucun favori pour le moment' },
    saveItemsLater: { en: 'Save items you like to view them later', ig: 'Chekwaa ihe ị masịrị ka ị hụ ha ma emesịa', yo: 'Fi àwọn nǹkan tí o fẹ́ràn pamọ́ láti wò wọ́n nígbà mìíràn', ha: 'Ajiye abubuwan da kake so don kallon su daga baya', fr: 'Enregistrez les articles que vous aimez pour les revoir plus tard' },
    startShopping: { en: 'Start Shopping', ig: 'Bido Ịzụ Ahịa', yo: 'Bẹ̀rẹ̀ Rírajà', ha: 'Fara siyayya', fr: 'Commencer les achats' },

    kycVerification: { en: 'KYC Verification', ig: 'Nnwapụta KYC', yo: 'Ìmúdájú KYC', ha: 'Tabbatar da KYC', fr: 'Vérification KYC' },
    secureAccount: { en: 'Secure your account', ig: 'Chekwaa akaụntụ gị', yo: 'Dáàbò bo àkáǹtì rẹ', ha: 'Kare asusun ka', fr: 'Sécurisez votre compte' },
    verificationProgress: { en: 'Verification Progress', ig: 'Ọganihu Nnwapụta', yo: 'Ìtẹ̀síwájú Ìmúdájú', ha: 'Ci gaban tabbatarwa', fr: 'Progression de la vérification' },
    emailVerification: { en: 'Email Verification', ig: 'Nnwapụta Email', yo: 'Ìmúdájú Ímeèlì', ha: 'Tabbatar da Imel', fr: "Vérification de l'e-mail" },
    phoneVerification: { en: 'Phone Verification', ig: 'Nnwapụta Ekwentị', yo: 'Ìmúdájú Fóònù', ha: 'Tabbatar da Waya', fr: 'Vérification du téléphone' },
    identityVerification: { en: 'Identity Verification', ig: 'Nnwapụta Njirimara', yo: 'Ìmúdájú Ìdánimọ̀', ha: 'Tabbatar da Shaida', fr: "Vérification d'identité" },
    businessVerification: { en: 'Business Verification', ig: 'Nnwapụta Azụmahịa', yo: 'Ìmúdájú Òwò', ha: 'Tabbatar da Kasuwanci', fr: "Vérification de l'entreprise" },
    continueToSubscription: { en: 'Continue to Subscription', ig: "Gaa N'ihu Na Ndenye Aha", yo: 'Tẹ̀síwájú sí Ìforúkọsílẹ̀', ha: 'Ci gaba zuwa biyan kuɗi', fr: "Continuer vers l'abonnement" },

    // New keys — web-only nav items not present in the mobile string
    // table (fall back to English there, since the mobile app doesn't
    // have these screens in the first place).
    browse: { en: 'Browse', ig: 'Chọgharịa', yo: 'Ṣàwárí', ha: 'Bincika', fr: 'Parcourir' },
    postAnAd: { en: 'Post an Ad', ig: 'Zipu Mgbasa Ozi', yo: 'Fi Ìpolówó Ránṣẹ́', ha: 'Tura talla', fr: 'Publier une annonce' },
    signOut: { en: 'Sign out', ig: 'Pụọ', yo: 'Jáde', ha: 'Fita', fr: 'Déconnexion' },
    verification: { en: 'Verification', ig: 'Nnwapụta', yo: 'Ìmúdájú', ha: 'Tabbatarwa', fr: 'Vérification' },
};

const STORAGE_KEY = 'sbrai_language';

export function getLanguage() {
    return localStorage.getItem(STORAGE_KEY) || 'en';
}

export function setLanguage(code) {
    localStorage.setItem(STORAGE_KEY, code);
    applyTranslations();
    window.dispatchEvent(new CustomEvent('sbrai:language-changed', { detail: { code } }));
}

export function t(key) {
    const lang = getLanguage();
    return TABLE[key]?.[lang] ?? TABLE[key]?.en ?? key;
}

/** Converts a backend category name ("Sharp Sand") to its translation
 * key ("cat_sharp_sand") — falls back to the original name untranslated
 * for categories not in the table (e.g. one an admin just added). */
export function translateCategory(name) {
    const key = 'cat_' + name.toLowerCase().trim().replace(/[^a-z0-9]+/g, '_');
    const lang = getLanguage();
    return TABLE[key]?.[lang] ?? TABLE[key]?.en ?? name;
}

function applyTranslations() {
    document.querySelectorAll('[data-i18n]').forEach((el) => {
        el.textContent = t(el.dataset.i18n);
    });
    document.querySelectorAll('[data-i18n-placeholder]').forEach((el) => {
        el.setAttribute('placeholder', t(el.dataset.i18nPlaceholder));
    });
    document.querySelectorAll('[data-i18n-category]').forEach((el) => {
        el.textContent = translateCategory(el.dataset.i18nCategory);
    });
}

export function initI18n() {
    const select = document.getElementById('language-select');
    if (select) {
        select.value = getLanguage();
        select.addEventListener('change', () => setLanguage(select.value));
    }
    applyTranslations();
}

export { LANGUAGES };
