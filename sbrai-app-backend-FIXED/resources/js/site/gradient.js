// Mirrors App\Models\Category::gradientFor() exactly — same hash, same
// palette — so a listing's card looks identical whether it was rendered
// server-side (home, listing detail) or client-side (browse, favourites).

const PALETTE = [
    ['#e8734a', '#c94f3a'], // coral
    ['#14b8a6', '#0d9488'], // teal
    ['#3b82f6', '#2563eb'], // blue
    ['#8b5cf6', '#7c3aed'], // purple
    ['#84a05a', '#6b8342'], // olive
    ['#38bdf8', '#0ea5e9'], // sky
];

export function gradientFor(name) {
    let hash = 0;
    for (let i = 0; i < name.length; i++) {
        hash = (hash * 31 + name.charCodeAt(i)) >>> 0;
    }
    const [from, to] = PALETTE[hash % PALETTE.length];
    return `linear-gradient(135deg, ${from}, ${to})`;
}
