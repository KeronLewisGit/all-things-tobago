import Alpine from 'alpinejs';

document.documentElement.classList.add('js');

const money = (n) => 'TT$' + Math.round(n).toLocaleString('en-US');

/** Currency toggle shared across the page (TTD or USD, display only). */
Alpine.store('currency', {
    code: (() => { try { return localStorage.getItem('att-currency') || 'TTD'; } catch { return 'TTD'; } })(),
    rate: Number(document.documentElement.dataset.usdRate || 6.8),
    toggle() {
        this.code = this.code === 'TTD' ? 'USD' : 'TTD';
        try { localStorage.setItem('att-currency', this.code); } catch {}
    },
    format(ttd) {
        if (this.code === 'USD') return 'US$' + Math.round(ttd / this.rate).toLocaleString('en-US');
        return money(ttd);
    },
});

/** Counter animation for the stats band. */
Alpine.data('countUp', (target, duration = 1400) => ({
    value: 0,
    start() {
        const begin = performance.now();
        const step = (now) => {
            const t = Math.min(1, (now - begin) / duration);
            this.value = Math.round(target * (1 - Math.pow(1 - t, 3)));
            if (t < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    },
}));

/**
 * Booking widget: date picker with blackout days, party size, live estimate.
 * price / priceType / min / max come from the experience; blackouts is an array of YYYY-MM-DD.
 */
Alpine.data('booking', (cfg) => ({
    price: cfg.price, priceType: cfg.priceType, min: cfg.min || 1, max: cfg.max || 10,
    blackouts: new Set(cfg.blackouts || []),
    adults: cfg.adults || 2, children: cfg.children || 0,
    date: cfg.date || '', month: null, slot: cfg.slot || 'morning',
    init() {
        const d = this.date ? new Date(this.date + 'T00:00:00') : new Date();
        this.month = new Date(d.getFullYear(), d.getMonth(), 1);
    },
    get guests() { return this.adults + this.children; },
    get estimate() {
        if (this.priceType === 'group') return this.price;
        return this.price * this.adults + this.price * 0.5 * this.children;
    },
    get estimateLabel() { return Alpine.store('currency').format(this.estimate); },
    get monthLabel() { return this.month.toLocaleDateString('en-GB', { month: 'long', year: 'numeric' }); },
    get dateLabel() { return this.date ? new Date(this.date + 'T00:00:00').toLocaleDateString('en-GB', { weekday: 'long', day: 'numeric', month: 'long' }) : 'Pick a date'; },
    prevMonth() { this.month = new Date(this.month.getFullYear(), this.month.getMonth() - 1, 1); },
    nextMonth() { this.month = new Date(this.month.getFullYear(), this.month.getMonth() + 1, 1); },
    get canPrev() { const now = new Date(); return this.month > new Date(now.getFullYear(), now.getMonth(), 1); },
    get cells() {
        const first = new Date(this.month.getFullYear(), this.month.getMonth(), 1);
        const offset = (first.getDay() + 6) % 7; // Monday first
        const daysInMonth = new Date(this.month.getFullYear(), this.month.getMonth() + 1, 0).getDate();
        const today = new Date(); today.setHours(0, 0, 0, 0);
        const cells = [];
        for (let i = 0; i < offset; i++) cells.push(null);
        for (let d = 1; d <= daysInMonth; d++) {
            const date = new Date(this.month.getFullYear(), this.month.getMonth(), d);
            const iso = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
            let state = 'open';
            if (date < today) state = 'past';
            else if (this.blackouts.has(iso)) state = 'full';
            if (iso === this.date) state = 'selected';
            cells.push({ d, iso, state });
        }
        return cells;
    },
    pick(cell) { if (cell && (cell.state === 'open' || cell.state === 'selected')) this.date = cell.iso; },
    inc(key) { if (this.guests < this.max) this[key]++; },
    dec(key, floor) { if (this[key] > floor) this[key]--; },
}));

/** Trip planner: pick several experiences, see the day's total. */
Alpine.data('planner', (cfg) => ({
    catalogue: cfg.experiences, picked: new Set(cfg.picked || []),
    adults: cfg.adults || 2, children: cfg.children || 0, filter: 'all',
    date: cfg.date || '', month: null, blackouts: new Set(cfg.blackouts || []), slot: 'morning',
    init() { const d = new Date(); this.month = new Date(d.getFullYear(), d.getMonth(), 1); },
    toggle(slug) { this.picked.has(slug) ? this.picked.delete(slug) : this.picked.add(slug); this.picked = new Set(this.picked); },
    has(slug) { return this.picked.has(slug); },
    get items() { return this.catalogue.filter((e) => this.picked.has(e.slug)); },
    subtotal(e) { return e.price_type === 'group' ? e.price : e.price * this.adults + e.price * 0.5 * this.children; },
    get total() { return this.items.reduce((sum, e) => sum + this.subtotal(e), 0); },
    get hours() { return this.items.reduce((sum, e) => sum + Number(e.duration_hours || 0), 0); },
    get visible() { return this.filter === 'all' ? this.catalogue : this.catalogue.filter((e) => e.category === this.filter); },
    fmt(n) { return Alpine.store('currency').format(n); },
    get guests() { return this.adults + this.children; },
    inc(key) { if (this.guests < 30) this[key]++; },
    dec(key, floor) { if (this[key] > floor) this[key]--; },
    get monthLabel() { return this.month.toLocaleDateString('en-GB', { month: 'long', year: 'numeric' }); },
    get dateLabel() { return this.date ? new Date(this.date + 'T00:00:00').toLocaleDateString('en-GB', { weekday: 'long', day: 'numeric', month: 'long' }) : 'Pick a date'; },
    prevMonth() { this.month = new Date(this.month.getFullYear(), this.month.getMonth() - 1, 1); },
    nextMonth() { this.month = new Date(this.month.getFullYear(), this.month.getMonth() + 1, 1); },
    get canPrev() { const now = new Date(); return this.month > new Date(now.getFullYear(), now.getMonth(), 1); },
    get cells() {
        const first = new Date(this.month.getFullYear(), this.month.getMonth(), 1);
        const offset = (first.getDay() + 6) % 7;
        const daysInMonth = new Date(this.month.getFullYear(), this.month.getMonth() + 1, 0).getDate();
        const today = new Date(); today.setHours(0, 0, 0, 0);
        const cells = [];
        for (let i = 0; i < offset; i++) cells.push(null);
        for (let d = 1; d <= daysInMonth; d++) {
            const date = new Date(this.month.getFullYear(), this.month.getMonth(), d);
            const iso = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
            let state = date < today ? 'past' : this.blackouts.has(iso) ? 'full' : 'open';
            if (iso === this.date) state = 'selected';
            cells.push({ d, iso, state });
        }
        return cells;
    },
    pick(cell) { if (cell && (cell.state === 'open' || cell.state === 'selected')) this.date = cell.iso; },
}));

/** Share / copy helpers for experience pages. */
Alpine.data('share', (title, url) => ({
    copied: false,
    async go() {
        if (navigator.share) { try { await navigator.share({ title, url }); return; } catch {} }
        try { await navigator.clipboard.writeText(url); this.copied = true; setTimeout(() => (this.copied = false), 1800); } catch {}
    },
}));

window.Alpine = Alpine;
Alpine.start();

/** Scroll reveal. */
const targets = document.querySelectorAll('[data-reveal]');
if ('IntersectionObserver' in window && targets.length) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => { if (entry.isIntersecting) { entry.target.classList.add('is-visible'); observer.unobserve(entry.target); } });
    }, { rootMargin: '0px 0px -10% 0px', threshold: 0.1 });
    targets.forEach((el) => observer.observe(el));
} else {
    targets.forEach((el) => el.classList.add('is-visible'));
}
