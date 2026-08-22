import { Head, usePage } from '@inertiajs/react';
import { Check, Quote } from 'lucide-react';

import SectionHeading from '@/components/section-heading';
import SiteLayout from '@/components/site-layout';
import { embedUrl, img } from '@/lib/site';
import type { SharedProps } from '@/types/site';

/**
 * Every block below is editable at /admin → Halaman → Tentang Kami; a section
 * with no content simply doesn't render.
 */
export default function About() {
    const { site } = usePage<SharedProps>().props;
    const page = site.page_about ?? {};
    const video = embedUrl(site.about_video);
    const fallbackImage =
        img(site.page_home?.about_image) ?? '/images/about-fasad.webp';

    return (
        <SiteLayout>
            <Head
                title={`${page.hero_title ?? 'Tentang Kami'} — ${site.brand_name}`}
            />

            <section className="bg-maroon py-12 text-center sm:py-16 lg:py-20">
                <div className="mx-auto max-w-3xl px-4">
                    <h1 className="text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-5xl">
                        {page.hero_title ?? 'Tentang Kami'}
                    </h1>
                    {page.hero_eyebrow && (
                        <p className="mt-3 text-sm font-bold tracking-widest text-gold uppercase">
                            {page.hero_eyebrow}
                        </p>
                    )}
                    <p className="mt-5 leading-relaxed text-maroon-100">
                        {site.tagline}
                    </p>
                </div>
            </section>

            {/* Profil */}
            <section className="py-14 sm:py-16 lg:py-24">
                <div className="mx-auto max-w-7xl px-4 lg:px-8">
                    <div className="grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
                        <div>
                            {page.eyebrow && (
                                <p className="text-xs font-bold tracking-widest text-maroon uppercase">
                                    {page.eyebrow}
                                </p>
                            )}
                            <h2 className="mt-3 text-2xl font-extrabold tracking-tight text-maroon-900 sm:text-3xl lg:text-4xl">
                                {page.title ?? site.brand_name}
                            </h2>
                            <p className="mt-4 leading-relaxed text-maroon-900/75 sm:text-justify">
                                {site.about_text}
                            </p>

                            <ul className="mt-7 grid gap-x-6 gap-y-4 sm:grid-cols-2">
                                {(site.about_points ?? []).map((point) => (
                                    <li
                                        key={point}
                                        className="flex items-start gap-2.5 font-bold text-maroon-900"
                                    >
                                        <span className="mt-0.5 grid size-5 shrink-0 place-items-center rounded-full bg-maroon text-white">
                                            <Check
                                                className="size-3"
                                                strokeWidth={3}
                                            />
                                        </span>
                                        {point}
                                    </li>
                                ))}
                            </ul>
                        </div>

                        {video ? (
                            <div className="aspect-video overflow-hidden rounded-3xl bg-maroon-900 shadow-xl">
                                <iframe
                                    src={video}
                                    title={`Profil ${site.brand_name}`}
                                    allowFullScreen
                                    loading="lazy"
                                    className="size-full"
                                />
                            </div>
                        ) : fallbackImage ? (
                            <div className="mx-auto w-full max-w-md overflow-hidden rounded-3xl shadow-xl ring-1 ring-maroon-100 lg:max-w-lg">
                                <img
                                    src={fallbackImage}
                                    alt={`Fasad rumah ${site.brand_name}`}
                                    width={900}
                                    height={900}
                                    loading="lazy"
                                    decoding="async"
                                    className="aspect-square w-full object-cover"
                                />
                            </div>
                        ) : null}
                    </div>

                    {/* Arti nama */}
                    {(page.name_parts ?? []).length > 0 && (
                        <div className="mt-14 sm:mt-16">
                            {page.name_title && (
                                <h3 className="text-center text-lg font-extrabold text-maroon-900 sm:text-xl">
                                    {page.name_title}
                                </h3>
                            )}
                            <div className="mt-6 grid gap-5 sm:grid-cols-3 sm:gap-6">
                                {(page.name_parts ?? []).map((part) => (
                                    <div
                                        key={part.word}
                                        className="rounded-2xl border border-maroon-100 bg-white p-6 shadow-sm"
                                    >
                                        <p className="text-xl font-extrabold tracking-wide text-maroon">
                                            {part.word}
                                        </p>
                                        {part.origin && (
                                            <p className="mt-1 text-[11px] font-semibold tracking-wide text-maroon-900/50 uppercase">
                                                {part.origin}
                                            </p>
                                        )}
                                        <p className="mt-3 text-sm font-bold text-maroon-900">
                                            {part.meaning}
                                        </p>
                                        <p className="mt-2 text-sm leading-relaxed text-maroon-900/70">
                                            {part.note}
                                        </p>
                                    </div>
                                ))}
                            </div>
                            {page.name_conclusion && (
                                <p className="mx-auto mt-6 max-w-3xl text-center text-base font-bold text-maroon-900 sm:text-lg">
                                    {page.name_conclusion}
                                </p>
                            )}
                        </div>
                    )}
                </div>
            </section>

            {/* Visi & Misi */}
            {(page.visi || (page.misi ?? []).length > 0) && (
                <section className="bg-maroon-50/60 py-14 sm:py-16 lg:py-24">
                    <div className="mx-auto max-w-7xl px-4 lg:px-8">
                        <SectionHeading
                            title={page.visi_title ?? 'Visi & Misi'}
                        />

                        {page.visi && (
                            <blockquote className="mx-auto max-w-4xl rounded-3xl bg-maroon p-8 text-center shadow-xl sm:p-10">
                                <Quote className="mx-auto size-9 text-gold" />
                                <p className="mt-4 text-lg leading-relaxed font-bold text-white sm:text-xl lg:text-2xl">
                                    {page.visi}
                                </p>
                                <footer className="mt-5 text-xs font-bold tracking-widest text-maroon-200 uppercase">
                                    {page.visi_label ?? 'Visi Perusahaan'}
                                </footer>
                            </blockquote>
                        )}

                        {(page.misi ?? []).length > 0 && (
                            <>
                                <h3 className="mt-14 text-center text-lg font-extrabold text-maroon-900 sm:text-xl">
                                    {page.misi_title ?? 'Misi Perusahaan'}
                                </h3>
                                <ol className="mt-6 grid gap-5 sm:grid-cols-2 sm:gap-6">
                                    {(page.misi ?? []).map((item, index) => (
                                        <li
                                            key={item.title}
                                            className="rounded-2xl border border-maroon-100 bg-white p-6 shadow-sm"
                                        >
                                            <span className="grid size-10 place-items-center rounded-xl bg-maroon text-base font-extrabold text-white">
                                                {index + 1}
                                            </span>
                                            <h4 className="mt-4 font-extrabold text-maroon-900">
                                                {item.title}
                                            </h4>
                                            <p className="mt-2 text-sm leading-relaxed text-maroon-900/70">
                                                {item.body}
                                            </p>
                                        </li>
                                    ))}
                                </ol>
                            </>
                        )}
                    </div>
                </section>
            )}

            {/* Budaya Kerja */}
            {(page.budaya ?? []).length > 0 && (
                <section className="py-14 sm:py-16 lg:py-24">
                    <div className="mx-auto max-w-7xl px-4 lg:px-8">
                        <SectionHeading
                            title={page.budaya_title ?? 'Budaya Kerja'}
                            subtitle={page.budaya_subtitle ?? undefined}
                        />
                        <div className="grid gap-5 sm:grid-cols-2 sm:gap-6 lg:grid-cols-3">
                            {(page.budaya ?? []).map((item) => (
                                <div
                                    key={`${item.letter}-${item.title}`}
                                    className="flex gap-4 rounded-2xl border border-maroon-100 bg-white p-6 shadow-sm transition hover:border-maroon-200 hover:shadow-md"
                                >
                                    <span
                                        aria-hidden="true"
                                        className="grid size-12 shrink-0 place-items-center rounded-2xl bg-maroon text-2xl font-extrabold text-white"
                                    >
                                        {item.letter}
                                    </span>
                                    <div>
                                        <h3 className="font-extrabold text-maroon-900">
                                            {item.title}
                                        </h3>
                                        {item.english && (
                                            <p className="text-xs font-semibold text-maroon italic">
                                                {item.english}
                                            </p>
                                        )}
                                        <p className="mt-2 text-sm leading-relaxed text-maroon-900/70">
                                            {item.body}
                                        </p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
            )}

            {/* Angka Kami */}
            {(site.stats ?? []).length > 0 && (
                <section className="bg-maroon-100/40 py-14 sm:py-16 lg:py-20">
                    <div className="mx-auto max-w-7xl px-4 lg:px-8">
                        <SectionHeading
                            title={page.stats_title ?? 'Angka Kami'}
                        />
                        <div className="grid grid-cols-2 gap-5 sm:gap-6 lg:grid-cols-4">
                            {(site.stats ?? []).map((stat) => (
                                <div
                                    key={stat.label}
                                    className="rounded-2xl bg-white p-6 text-center shadow-md"
                                >
                                    <p className="text-3xl font-extrabold text-maroon sm:text-4xl">
                                        {stat.value}
                                    </p>
                                    <p className="mt-1 text-sm font-semibold text-maroon-900/60">
                                        {stat.label}
                                    </p>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
            )}
        </SiteLayout>
    );
}
