import { Head, usePage } from '@inertiajs/react';

import { img } from '@/lib/site';
import type { SharedProps, Shortlink } from '@/types/site';

/** Halaman link-in-bio (/l/{slug}), diatur di CMS → Proyek → Shortlink. */
export default function ShortlinkPage({ shortlink }: { shortlink: Shortlink }) {
    const { site } = usePage<SharedProps>().props;
    const logo = img(shortlink.logo ?? site.logo);
    const background = img(shortlink.background);
    const color = shortlink.button_color || 'var(--color-maroon)';

    return (
        <>
            <Head title={`${shortlink.title} — ${site.brand_name}`} />

            <div className="relative isolate min-h-dvh bg-maroon-900 text-white">
                {background && (
                    <img
                        src={background}
                        alt=""
                        className="fixed inset-0 -z-10 size-full object-cover"
                    />
                )}
                <div className="fixed inset-0 -z-10 bg-neutral-900/60" />

                <main className="mx-auto flex max-w-3xl flex-col items-center px-4 pt-8 pb-10 text-center sm:pt-10">
                    {logo && (
                        <img
                            src={logo}
                            alt={shortlink.title}
                            className="size-20 rounded-full bg-white object-contain p-1.5 shadow-lg sm:size-24"
                        />
                    )}
                    {shortlink.handle && (
                        <p className="mt-3 text-lg font-semibold text-white/80">
                            {shortlink.handle}
                        </p>
                    )}
                    <h1 className="mt-4 text-2xl font-extrabold tracking-tight uppercase sm:text-3xl">
                        {shortlink.title}
                    </h1>
                    {shortlink.subtitle && (
                        <p className="mt-2 max-w-xl text-sm text-white/85 sm:text-base">
                            {shortlink.subtitle}
                        </p>
                    )}

                    <ul className="mt-6 grid w-full gap-4">
                        {(shortlink.links ?? []).map((link) => (
                            <li key={`${link.url}-${link.title}`}>
                                <a
                                    href={link.url}
                                    target="_blank"
                                    rel="noreferrer"
                                    style={{
                                        backgroundColor: `color-mix(in srgb, ${color} 72%, transparent)`,
                                    }}
                                    className="relative flex min-h-16 items-center justify-center rounded-md px-16 py-3 shadow-sm backdrop-blur-sm transition hover:brightness-110 active:scale-[0.99]"
                                >
                                    {link.icon && (
                                        <i
                                            aria-hidden="true"
                                            className={`${link.icon} absolute left-4 flex text-3xl sm:left-5`}
                                        />
                                    )}
                                    <span>
                                        <span className="block font-bold uppercase">
                                            {link.title}
                                        </span>
                                        {link.subtitle && (
                                            <span className="block text-sm text-white/80">
                                                {link.subtitle}
                                            </span>
                                        )}
                                    </span>
                                </a>
                            </li>
                        ))}
                    </ul>

                    {/* Kredit wajib dari lisensi gratis Flaticon UIcons. */}
                    <p className="mt-10 text-xs text-white/60">
                        {site.brand_name} · Ikon oleh{' '}
                        <a
                            href="https://www.flaticon.com/uicons"
                            target="_blank"
                            rel="noreferrer"
                            className="underline underline-offset-2 hover:text-white"
                        >
                            Flaticon
                        </a>
                    </p>
                </main>
            </div>
        </>
    );
}
