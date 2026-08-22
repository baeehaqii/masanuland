import type { Site } from '@/types/site';

/** Storage-relative upload path → public URL. Absolute URLs pass through. */
export function img(path?: string | null): string | undefined {
    if (!path) {
        return undefined;
    }

    return /^(https?:)?\/\/|^\//.test(path) ? path : `/storage/${path}`;
}

export function rupiah(value?: number | null): string | null {
    if (!value) {
        return null;
    }

    return `Rp ${value.toLocaleString('id-ID')} ,-`;
}

export function waLink(site: Site, text?: string): string {
    const number = (site.whatsapp ?? '').replace(/\D/g, '');
    const message =
        text ??
        site.whatsapp_text ??
        `Halo ${site.brand_name}, mohon informasi perumahannya.`;

    return `https://api.whatsapp.com/send/?phone=${number}&text=${encodeURIComponent(message)}`;
}

/** YouTube watch/share links → embeddable URL. */
export function embedUrl(url?: string | null): string | undefined {
    if (!url) {
        return undefined;
    }

    const id = url.match(/(?:v=|youtu\.be\/|embed\/|shorts\/)([\w-]{11})/)?.[1];

    return id ? `https://www.youtube.com/embed/${id}` : url;
}

/**
 * Google Maps memberi kode `<iframe ...>` lengkap saat menekan "Bagikan → Sematkan",
 * dan itu yang biasanya ditempel ke CMS. Ambil src-nya; kalau yang ditempel memang
 * sudah berupa URL, dipakai apa adanya.
 */
export function mapSrc(value?: string | null): string | undefined {
    if (!value) {
        return undefined;
    }

    return value.match(/src=["']([^"']+)["']/)?.[1] ?? value;
}
