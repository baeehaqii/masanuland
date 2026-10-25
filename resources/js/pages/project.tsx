import { Head, usePage } from '@inertiajs/react';
import {
    Building2,
    Check,
    ChevronLeft,
    ChevronRight,
    Clock,
    Download,
    Expand,
    MapPin,
    MessageCircle,
    Navigation,
} from 'lucide-react';
import { useState } from 'react';
import type { FormEvent } from 'react';

import HouseTypeCard from '@/components/house-type-card';
import ImageLightbox from '@/components/image-lightbox';
import type { LightboxState } from '@/components/image-lightbox';
import SectionHeading from '@/components/section-heading';
import SiteLayout from '@/components/site-layout';
import { embedUrl, img, mapSrc, rupiah, waLink } from '@/lib/site';
import type { Project, SharedProps } from '@/types/site';

type TabId = 'siteplan' | 'denah';

/** 1 tipe di tengah, 2 tipe berdua, 3+ tipe tiga per baris. */
const typeGrid: Record<number, string> = {
    1: 'mx-auto max-w-xl',
    2: 'md:grid-cols-2',
    3: 'md:grid-cols-2 lg:grid-cols-3',
};

export default function ProjectPage({ project }: { project: Project }) {
    const { site } = usePage<SharedProps>().props;
    const page = site.page_project ?? {};
    const tabs: { id: TabId; label: string }[] = [
        { id: 'siteplan', label: page.siteplan_tab ?? 'Siteplan' },
        { id: 'denah', label: page.types_tab ?? 'Denah & Tipe' },
    ];
    const sitePlan = img(project.site_plan_image);
    const [tab, setTab] = useState<TabId>(sitePlan ? 'siteplan' : 'denah');
    const photos = [
        ...new Set(
            [
                project.hero_image ?? project.card_image,
                ...(project.gallery ?? []),
            ]
                .map(img)
                .filter(Boolean) as string[],
        ),
    ];
    const [active, setActive] = useState(0);
    const step = (by: number) =>
        setActive((active + by + photos.length) % photos.length);
    const [lightbox, setLightbox] = useState<LightboxState | null>(null);
    const openPhotos = (index: number) =>
        setLightbox({ images: photos, index, alt: project.name });
    const progressVideos = project.progress_videos ?? [];
    const map = mapSrc(project.map_embed);
    const description = project.description ?? '';
    // Kira-kira 3 baris; deskripsi pendek tidak perlu tombol "Selengkapnya".
    const longDescription = description.replace(/<[^>]+>/g, '').length > 320;
    const [expanded, setExpanded] = useState(false);
    const wa = (extra?: string) =>
        waLink(
            site,
            `Halo ${site.brand_name}, mohon informasi ${project.name}${extra ? ` (${extra})` : ''}. Saya dapat informasi dari WEBSITE.`,
        );
    const sendInquiry = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        const form = new FormData(event.currentTarget);
        const phone = String(form.get('phone') ?? '').trim();
        window.open(
            waLink(
                site,
                `Halo ${site.brand_name}, saya ${String(form.get('name')).trim()}${phone ? ` (${phone})` : ''}.\n${String(form.get('message')).trim()}\n\nPerumahan: ${project.name}. Saya dapat informasi dari WEBSITE.`,
            ),
            '_blank',
            'noreferrer',
        );
    };

    return (
        <SiteLayout>
            <Head title={`${project.name} — ${site.brand_name}`} />

            {/* Ringkasan: galeri, info, form & peta */}
            <section className="bg-maroon-50/60 py-6 sm:py-8 lg:py-10">
                <div className="mx-auto grid max-w-7xl gap-6 px-4 lg:grid-cols-3 lg:items-start lg:px-8">
                    <div className="rounded-3xl border border-maroon-100 bg-white p-3 shadow-sm sm:p-5 lg:col-span-2">
                        {photos.length > 0 ? (
                            <div className="grid gap-3 sm:grid-cols-4">
                                <div
                                    className={`relative overflow-hidden rounded-2xl bg-maroon-50 ${photos.length > 1 ? 'sm:col-span-3' : 'sm:col-span-4'}`}
                                >
                                    <img
                                        src={photos[active]}
                                        alt={project.name}
                                        fetchPriority="high"
                                        className="aspect-4/3 w-full object-cover sm:aspect-auto sm:h-full sm:min-h-[22rem] lg:min-h-[28rem]"
                                    />
                                    <button
                                        type="button"
                                        onClick={() => openPhotos(active)}
                                        aria-label="Lihat foto ukuran penuh"
                                        className="absolute bottom-3 left-3 grid size-11 cursor-pointer place-items-center rounded-full bg-white/90 text-maroon-900 shadow transition hover:bg-white"
                                    >
                                        <Expand className="size-5" />
                                    </button>
                                    {photos.length > 1 && (
                                        <>
                                            <div className="absolute inset-x-0 bottom-5 flex justify-center gap-1.5">
                                                {photos.map((photo, index) => (
                                                    <span
                                                        key={photo}
                                                        className={`h-1.5 rounded-full transition-all ${index === active ? 'w-6 bg-gold' : 'w-1.5 bg-white/80'}`}
                                                    />
                                                ))}
                                            </div>
                                            <div className="absolute right-3 bottom-3 flex gap-2">
                                                <button
                                                    type="button"
                                                    aria-label="Foto sebelumnya"
                                                    onClick={() => step(-1)}
                                                    className="grid size-11 cursor-pointer place-items-center rounded-full bg-white/90 text-maroon-900 shadow transition hover:bg-white"
                                                >
                                                    <ChevronLeft className="size-5" />
                                                </button>
                                                <button
                                                    type="button"
                                                    aria-label="Foto berikutnya"
                                                    onClick={() => step(1)}
                                                    className="grid size-11 cursor-pointer place-items-center rounded-full bg-white/90 text-maroon-900 shadow transition hover:bg-white"
                                                >
                                                    <ChevronRight className="size-5" />
                                                </button>
                                            </div>
                                        </>
                                    )}
                                </div>

                                {photos.length > 1 && (
                                    <div className="grid grid-cols-4 gap-3 sm:grid-cols-1 sm:grid-rows-4">
                                        {photos.slice(1, 5).map((photo, i) => {
                                            const index = i + 1;
                                            const more = photos.length - 5;
                                            const isMore =
                                                index === 4 && more > 0;

                                            return (
                                                <button
                                                    key={photo}
                                                    type="button"
                                                    onClick={() =>
                                                        isMore
                                                            ? openPhotos(index)
                                                            : setActive(index)
                                                    }
                                                    aria-label={
                                                        isMore
                                                            ? `Lihat ${more} foto lainnya`
                                                            : `Tampilkan foto ${index + 1}`
                                                    }
                                                    className={`relative cursor-pointer overflow-hidden rounded-xl ring-2 transition ${active === index ? 'ring-gold' : 'ring-transparent hover:ring-maroon-200'}`}
                                                >
                                                    <img
                                                        src={photo}
                                                        alt=""
                                                        loading="lazy"
                                                        className={`aspect-4/3 size-full object-cover sm:aspect-auto ${isMore ? 'scale-110 blur-sm' : ''}`}
                                                    />
                                                    {isMore && (
                                                        <span className="absolute inset-0 grid place-items-center">
                                                            <span className="grid size-10 place-items-center rounded-full bg-gold text-sm font-extrabold text-maroon-900">
                                                                +{more}
                                                            </span>
                                                        </span>
                                                    )}
                                                </button>
                                            );
                                        })}
                                    </div>
                                )}
                            </div>
                        ) : (
                            <div className="flex items-center gap-3 rounded-2xl bg-maroon-50 px-5 py-4 text-sm text-maroon-900/70">
                                <Building2 className="size-5 shrink-0 text-maroon" />
                                Foto perumahan ini belum diunggah lewat CMS.
                            </div>
                        )}

                        <div className="px-1 pt-6 sm:px-2">
                            <h1 className="text-3xl font-extrabold tracking-tight text-maroon-900 sm:text-4xl lg:text-5xl">
                                {project.name}
                            </h1>

                            <div className="mt-4 flex flex-wrap items-end justify-between gap-3">
                                <div>
                                    <p className="text-xs font-semibold tracking-widest text-maroon-900/50 uppercase">
                                        {page.price_label ?? 'Harga Mulai'}
                                        {project.price_before && (
                                            <span className="ml-2 tracking-normal normal-case line-through">
                                                {rupiah(project.price_before)}
                                            </span>
                                        )}
                                    </p>
                                    <p className="text-2xl font-extrabold whitespace-nowrap text-maroon sm:text-3xl">
                                        {rupiah(project.price_from) ??
                                            page.price_empty ??
                                            'Hubungi CS'}
                                    </p>
                                    {project.price_note && (
                                        <p className="mt-1 text-xs font-medium text-gold-dark">
                                            {project.price_note}
                                        </p>
                                    )}
                                </div>
                                {(project.badges ?? []).length > 0 && (
                                    <div className="flex flex-wrap gap-2">
                                        {project.badges!.map((badge) => (
                                            <span
                                                key={badge}
                                                className="inline-flex items-center gap-1.5 rounded-full bg-maroon-50 px-3 py-1 text-sm font-semibold text-maroon"
                                            >
                                                <span className="size-1.5 rounded-full bg-gold" />
                                                {badge}
                                            </span>
                                        ))}
                                    </div>
                                )}
                            </div>

                            {(project.location || project.tagline) && (
                                <p className="mt-3 flex items-start gap-2 text-maroon-900/70">
                                    <MapPin className="mt-0.5 size-5 shrink-0 text-maroon" />
                                    {[project.location, project.tagline]
                                        .filter(Boolean)
                                        .join(' · ')}
                                </p>
                            )}

                            {(project.distances ?? []).length > 0 && (
                                <div className="mt-8">
                                    <h2 className="text-lg font-extrabold text-maroon-900">
                                        {page.info_title ?? 'Info Umum'}
                                    </h2>
                                    <ul className="mt-3 flex flex-wrap gap-x-5 gap-y-3 text-maroon-900/80">
                                        {project.distances!.map((distance) => (
                                            <li
                                                key={distance.place}
                                                className="flex items-center gap-2"
                                            >
                                                <Clock className="size-5 text-maroon" />
                                                <span>
                                                    <strong className="font-bold text-maroon-900">
                                                        {distance.minutes}{' '}
                                                        {page.minutes_suffix ??
                                                            'Menit'}
                                                    </strong>{' '}
                                                    ke {distance.place}
                                                </span>
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            )}

                            {description && (
                                <div className="mt-8">
                                    <h2 className="text-lg font-extrabold text-maroon-900">
                                        {page.about_title ??
                                            'Tentang Perumahan'}
                                    </h2>
                                    <div
                                        className={`prose prose-maroon mt-2 max-w-none text-maroon-900/80 ${longDescription && !expanded ? 'line-clamp-3' : ''}`}
                                        dangerouslySetInnerHTML={{
                                            __html: description,
                                        }}
                                    />
                                    {longDescription && (
                                        <button
                                            type="button"
                                            onClick={() =>
                                                setExpanded(!expanded)
                                            }
                                            className="mt-1 cursor-pointer font-semibold text-maroon underline underline-offset-4 hover:text-maroon-700"
                                        >
                                            {expanded
                                                ? 'Sembunyikan'
                                                : 'Selengkapnya'}
                                        </button>
                                    )}
                                </div>
                            )}

                            <div className="mt-8 flex flex-wrap gap-3 pb-2">
                                {project.brochure_url && (
                                    <a
                                        href={project.brochure_url}
                                        target="_blank"
                                        rel="noreferrer"
                                        className="inline-flex min-h-12 cursor-pointer items-center gap-2 rounded-full border-2 border-maroon-900 px-6 font-bold text-maroon-900 transition hover:bg-maroon-900 hover:text-white active:scale-[0.98]"
                                    >
                                        <Download className="size-5" />{' '}
                                        {page.brochure_label ??
                                            'Download Brosur'}
                                    </a>
                                )}
                                <a
                                    href={wa()}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="inline-flex min-h-12 cursor-pointer items-center gap-2 rounded-full bg-wa px-6 font-bold text-white transition hover:bg-wa-dark active:scale-[0.98]"
                                >
                                    <MessageCircle className="size-5" />{' '}
                                    {page.contact_label ?? 'Hubungi Kami'}
                                </a>
                            </div>
                        </div>
                    </div>

                    <aside className="grid gap-6 lg:sticky lg:top-24">
                        <form
                            onSubmit={sendInquiry}
                            className="rounded-3xl border border-maroon-100 bg-white p-5 shadow-sm sm:p-6"
                        >
                            <h2 className="text-xl font-extrabold text-maroon-900">
                                {page.inquiry_title ?? 'Minta Informasi'}
                            </h2>
                            <div className="mt-4 grid gap-3">
                                <label className="grid gap-1.5 text-sm font-semibold text-maroon-900">
                                    Nama Lengkap*
                                    <input
                                        name="name"
                                        required
                                        autoComplete="name"
                                        placeholder="Nama Anda"
                                        className="min-h-12 rounded-xl border border-maroon-100 bg-maroon-50/40 px-4 font-normal outline-none focus:border-maroon focus:ring-2 focus:ring-maroon/20"
                                    />
                                </label>
                                <label className="grid gap-1.5 text-sm font-semibold text-maroon-900">
                                    No. WhatsApp
                                    <input
                                        name="phone"
                                        type="tel"
                                        inputMode="tel"
                                        autoComplete="tel"
                                        placeholder="0812-3456-7890"
                                        className="min-h-12 rounded-xl border border-maroon-100 bg-maroon-50/40 px-4 font-normal outline-none focus:border-maroon focus:ring-2 focus:ring-maroon/20"
                                    />
                                </label>
                                <label className="grid gap-1.5 text-sm font-semibold text-maroon-900">
                                    Pesan
                                    <textarea
                                        name="message"
                                        rows={4}
                                        defaultValue={`Saya tertarik dengan ${project.name} dan ingin info ketersediaan unit, harga, dan langkah selanjutnya.`}
                                        className="rounded-xl border border-maroon-100 bg-maroon-50/40 px-4 py-3 font-normal outline-none focus:border-maroon focus:ring-2 focus:ring-maroon/20"
                                    />
                                </label>
                            </div>
                            <p className="mt-3 text-xs leading-relaxed text-maroon-900/60">
                                Pesan dikirim lewat WhatsApp ke tim marketing{' '}
                                {site.brand_name}.
                            </p>
                            <button
                                type="submit"
                                className="mt-4 inline-flex min-h-12 w-full cursor-pointer items-center justify-center gap-2 rounded-full bg-wa px-6 font-bold text-white transition hover:bg-wa-dark active:scale-[0.98]"
                            >
                                <MessageCircle className="size-5" />
                                {page.inquiry_button ?? 'Kirim via WhatsApp'}
                            </button>
                        </form>

                        {(map || project.map_url) && (
                            <div className="overflow-hidden rounded-3xl border border-maroon-100 bg-white shadow-sm">
                                {map && (
                                    <iframe
                                        src={map}
                                        title={`Peta ${project.name}`}
                                        loading="lazy"
                                        className="aspect-square w-full lg:aspect-4/3"
                                    />
                                )}
                                {project.map_url && (
                                    <a
                                        href={project.map_url}
                                        target="_blank"
                                        rel="noreferrer"
                                        className="flex min-h-14 items-center justify-between gap-3 px-5 font-bold text-maroon-900 transition hover:bg-maroon-50"
                                    >
                                        {page.maps_label ??
                                            'Petunjuk arah ke lokasi'}
                                        <Navigation className="size-5 text-maroon" />
                                    </a>
                                )}
                            </div>
                        )}
                    </aside>
                </div>
            </section>

            {/* Features */}
            {(project.features ?? []).length > 0 && (
                <section className="pt-12 pb-14 sm:pt-14 sm:pb-16 lg:pt-16 lg:pb-24">
                    <div className="mx-auto max-w-7xl px-4 lg:px-8">
                        <SectionHeading
                            title={
                                page.features_title ?? 'Fasilitas & Keunggulan'
                            }
                        />
                        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            {project.features!.map((feature) => (
                                <div
                                    key={feature.label}
                                    className="flex items-center gap-3 rounded-xl border border-maroon-100 bg-white p-4 shadow-sm"
                                >
                                    {feature.icon ? (
                                        <img
                                            src={img(feature.icon)}
                                            alt=""
                                            loading="lazy"
                                            className="size-10 shrink-0 object-contain"
                                        />
                                    ) : (
                                        <span className="grid size-8 shrink-0 place-items-center rounded-full bg-maroon text-white">
                                            <Check
                                                className="size-4"
                                                strokeWidth={3}
                                            />
                                        </span>
                                    )}
                                    <span className="font-semibold">
                                        {feature.label}
                                    </span>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
            )}
            {/* Siteplan & denah tabs */}
            {(sitePlan || (project.house_types ?? []).length > 0) && (
                <section className="bg-maroon-50/60 py-14 sm:py-16 lg:py-24">
                    <div className="mx-auto max-w-7xl px-4 lg:px-8">
                        <SectionHeading
                            title={page.siteplan_title ?? 'Siteplan & Denah'}
                        />

                        <div
                            role="tablist"
                            aria-label="Siteplan dan denah tipe"
                            className="mx-auto mb-8 flex max-w-xl gap-1.5 rounded-full bg-white p-1.5 shadow-sm ring-1 ring-maroon-100"
                        >
                            {tabs.map((item) => (
                                <button
                                    key={item.id}
                                    type="button"
                                    role="tab"
                                    id={`tab-${item.id}`}
                                    aria-selected={tab === item.id}
                                    aria-controls={`panel-${item.id}`}
                                    onClick={() => setTab(item.id)}
                                    className={`min-h-11 flex-1 cursor-pointer rounded-full px-4 text-sm font-bold transition sm:text-base ${
                                        tab === item.id
                                            ? 'bg-maroon text-white shadow'
                                            : 'text-maroon-900 hover:bg-maroon-50'
                                    }`}
                                >
                                    {item.label}
                                </button>
                            ))}
                        </div>

                        {tab === 'siteplan' ? (
                            <div
                                role="tabpanel"
                                id="panel-siteplan"
                                aria-labelledby="tab-siteplan"
                                className="mx-auto max-w-5xl"
                            >
                                {sitePlan ? (
                                    <button
                                        type="button"
                                        onClick={() =>
                                            setLightbox({
                                                images: [sitePlan],
                                                index: 0,
                                                alt: `Site plan ${project.name}`,
                                            })
                                        }
                                        aria-label="Perbesar site plan"
                                        className="block w-full cursor-zoom-in"
                                    >
                                        <img
                                            src={sitePlan}
                                            alt={`Site plan ${project.name}`}
                                            loading="lazy"
                                            decoding="async"
                                            className="w-full rounded-2xl border border-maroon-100 bg-white shadow-lg"
                                        />
                                    </button>
                                ) : (
                                    <p className="rounded-2xl border border-maroon-100 bg-white px-5 py-8 text-center text-sm text-maroon-900/60">
                                        Site plan belum diunggah.
                                    </p>
                                )}
                            </div>
                        ) : (
                            <div
                                role="tabpanel"
                                id="panel-denah"
                                aria-labelledby="tab-denah"
                            >
                                <div
                                    className={`grid gap-6 ${typeGrid[Math.min(project.house_types!.length, 3)]}`}
                                >
                                    {project.house_types!.map((type) => (
                                        <HouseTypeCard
                                            key={type.id}
                                            type={type}
                                            brochureUrl={
                                                type.brochure_url ??
                                                project.brochure_url
                                            }
                                            waHref={wa(type.name)}
                                            priceLabel={
                                                page.price_label ?? 'Harga'
                                            }
                                            priceEmpty={
                                                page.price_empty ?? 'Hubungi CS'
                                            }
                                            onOpen={setLightbox}
                                        />
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>
                </section>
            )}

            {/* Progres pembangunan (YouTube Shorts) */}
            {progressVideos.length > 0 && (
                <section className="py-14 sm:py-16 lg:py-24">
                    <div className="mx-auto max-w-7xl px-4 lg:px-8">
                        <SectionHeading
                            title={page.progress_title ?? 'Progres Pembangunan'}
                        />
                        <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                            {progressVideos.map((video) => (
                                <figure key={`${video.url}-${video.label}`}>
                                    <div className="aspect-9/16 overflow-hidden rounded-2xl bg-maroon-900 shadow-lg">
                                        <iframe
                                            src={embedUrl(video.url)}
                                            title={`${video.label} — ${project.name}`}
                                            allowFullScreen
                                            loading="lazy"
                                            className="size-full"
                                        />
                                    </div>
                                    <figcaption className="mt-3 text-center text-sm font-bold text-maroon-900">
                                        {video.label}
                                    </figcaption>
                                </figure>
                            ))}
                        </div>
                    </div>
                </section>
            )}

            <ImageLightbox state={lightbox} onChange={setLightbox} />
        </SiteLayout>
    );
}
