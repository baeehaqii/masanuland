import { Head, usePage } from '@inertiajs/react';
import {
    Building2,
    Check,
    Clock,
    Download,
    Home as HomeIcon,
    MapPin,
    MessageCircle,
} from 'lucide-react';
import { useState } from 'react';

import SectionHeading from '@/components/section-heading';
import SiteLayout from '@/components/site-layout';
import { embedUrl, img, mapSrc, rupiah, waLink } from '@/lib/site';
import type { Project, SharedProps } from '@/types/site';

const tabs = [
    { id: 'siteplan', label: 'Siteplan' },
    { id: 'denah', label: 'Denah & Tipe' },
] as const;

export default function ProjectPage({ project }: { project: Project }) {
    const { site } = usePage<SharedProps>().props;
    const hero = img(project.hero_image ?? project.card_image);
    const sitePlan = img(project.site_plan_image);
    const [tab, setTab] = useState<(typeof tabs)[number]['id']>(
        sitePlan ? 'siteplan' : 'denah',
    );
    const gallery = (project.gallery ?? [])
        .map(img)
        .filter(Boolean) as string[];
    const updateVideo = embedUrl(project.update_video);
    const locationVideo = embedUrl(project.location_video);
    const map = mapSrc(project.map_embed);
    const wa = (extra?: string) =>
        waLink(
            site,
            `Halo ${site.brand_name}, mohon informasi ${project.name}${extra ? ` (${extra})` : ''}. Saya dapat informasi dari WEBSITE.`,
        );

    return (
        <SiteLayout>
            <Head title={`${project.name} — ${site.brand_name}`} />

            {/* Hero */}
            <section className="relative isolate overflow-hidden bg-maroon lg:flex lg:min-h-[34rem] lg:items-center xl:min-h-[44rem]">
                {hero && (
                    <img
                        src={hero}
                        alt={project.name}
                        fetchPriority="high"
                        className="aspect-video w-full object-cover lg:absolute lg:inset-0 lg:aspect-auto lg:size-full"
                    />
                )}
                <div
                    aria-hidden="true"
                    className="absolute inset-0 bg-linear-to-t from-maroon-900/90 from-0% to-transparent to-20%"
                />
                <div className="relative mx-auto w-full max-w-7xl px-4 pt-8 pb-12 sm:pt-10 sm:pb-14 lg:px-8 lg:py-20">
                    <div className="rounded-3xl bg-maroon-900/60 p-6 ring-1 ring-white/20 backdrop-blur-md sm:p-8 lg:max-w-2xl">
                        {project.location && (
                            <p className="text-xs font-bold tracking-widest text-gold uppercase">
                                {project.location}
                            </p>
                        )}
                        <h1 className="mt-2 text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-6xl">
                            {project.name}
                        </h1>
                        {project.tagline && (
                            <p className="mt-3 text-base text-maroon-100 sm:text-lg">
                                {project.tagline}
                            </p>
                        )}

                        <div className="mt-6 flex flex-wrap gap-2">
                            {(project.badges ?? []).map((badge) => (
                                <span
                                    key={badge}
                                    className="rounded-full bg-maroon-900/60 px-4 py-1.5 text-sm font-semibold text-white ring-1 ring-white/25 backdrop-blur-sm"
                                >
                                    {badge}
                                </span>
                            ))}
                        </div>

                        <div className="mt-8 inline-block rounded-2xl bg-maroon-900/70 px-6 py-4 ring-1 ring-white/20 backdrop-blur-md">
                            <p className="text-xs font-semibold tracking-widest text-maroon-100 uppercase">
                                Harga Mulai
                            </p>
                            {project.price_before && (
                                <p className="text-sm text-gold line-through">
                                    {rupiah(project.price_before)}
                                </p>
                            )}
                            <p className="text-2xl font-extrabold text-white sm:text-3xl">
                                {rupiah(project.price_from) ?? 'Hubungi CS'}
                            </p>
                            {project.price_note && (
                                <p className="mt-1 text-xs font-medium text-gold">
                                    {project.price_note}
                                </p>
                            )}
                        </div>

                        <div className="mt-8 flex flex-wrap gap-3">
                            {project.brochure_url && (
                                <a
                                    href={project.brochure_url}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="inline-flex min-h-12 cursor-pointer items-center gap-2 rounded-full bg-gold px-6 font-bold text-maroon-900 transition hover:bg-gold-dark active:scale-[0.98]"
                                >
                                    <Download className="size-5" /> Download
                                    Brosur
                                </a>
                            )}
                            <a
                                href={wa()}
                                target="_blank"
                                rel="noreferrer"
                                className="inline-flex min-h-12 cursor-pointer items-center gap-2 rounded-full bg-wa px-6 font-bold text-white transition hover:bg-wa-dark active:scale-[0.98]"
                            >
                                <MessageCircle className="size-5" /> Hubungi
                                Kami
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            {/* Distances */}
            {(project.distances ?? []).length > 0 && (
                <section className="border-b border-maroon-100 bg-maroon-50/60 py-10">
                    <div className="mx-auto grid max-w-7xl gap-4 px-4 sm:grid-cols-2 lg:grid-cols-4 lg:px-8">
                        {project.distances!.map((distance) => (
                            <div
                                key={distance.place}
                                className="flex items-center gap-3 rounded-xl border border-maroon-100 bg-white p-4 shadow-sm"
                            >
                                <Clock className="size-6 shrink-0 text-maroon" />
                                <div>
                                    <p className="font-extrabold text-maroon">
                                        {distance.minutes} Menit
                                    </p>
                                    <p className="text-xs text-maroon-900/70">
                                        ke {distance.place}
                                    </p>
                                </div>
                            </div>
                        ))}
                    </div>
                </section>
            )}

            {/* Description */}
            {project.description && (
                <section className="py-14 sm:py-16 lg:py-24">
                    <div
                        className="prose prose-maroon mx-auto max-w-3xl px-4 text-maroon-900/80"
                        dangerouslySetInnerHTML={{
                            __html: project.description,
                        }}
                    />
                </section>
            )}

            {/* Features */}
            {(project.features ?? []).length > 0 && (
                <section className="py-14 sm:py-16 lg:py-24">
                    <div className="mx-auto max-w-7xl px-4 lg:px-8">
                        <SectionHeading title="Fasilitas & Keunggulan" />
                        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            {project.features!.map((feature) => (
                                <div
                                    key={feature}
                                    className="flex items-center gap-3 rounded-xl border border-maroon-100 bg-white p-4 shadow-sm"
                                >
                                    <span className="grid size-8 shrink-0 place-items-center rounded-full bg-maroon text-white">
                                        <Check
                                            className="size-4"
                                            strokeWidth={3}
                                        />
                                    </span>
                                    <span className="font-semibold">
                                        {feature}
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
                        <SectionHeading title="Siteplan & Denah" />

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
                                    <img
                                        src={sitePlan}
                                        alt={`Site plan ${project.name}`}
                                        loading="lazy"
                                        decoding="async"
                                        className="w-full rounded-2xl border border-maroon-100 bg-white shadow-lg"
                                    />
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
                                <div className="grid gap-6 lg:grid-cols-2">
                                    {project.house_types!.map((type) => {
                                        const photo = img(type.image);

                                        return (
                                            <article
                                                key={type.id}
                                                className="overflow-hidden rounded-2xl border border-maroon-100 bg-white shadow-sm"
                                            >
                                                <div className="aspect-16/10 bg-maroon-50">
                                                    {photo ? (
                                                        <img
                                                            src={photo}
                                                            alt={type.name}
                                                            loading="lazy"
                                                            className="size-full object-cover"
                                                        />
                                                    ) : (
                                                        <div className="grid size-full place-items-center text-maroon-200">
                                                            <HomeIcon className="size-10" />
                                                        </div>
                                                    )}
                                                </div>

                                                <div className="p-6">
                                                    <h3 className="text-xl font-extrabold text-maroon-900 sm:text-2xl">
                                                        {type.name}
                                                    </h3>

                                                    <dl className="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
                                                        {(type.specs ?? []).map(
                                                            (spec) => (
                                                                <div
                                                                    key={
                                                                        spec.label
                                                                    }
                                                                    className="rounded-xl bg-maroon-50 px-3 py-2.5 text-center"
                                                                >
                                                                    <dt className="text-lg font-extrabold text-maroon">
                                                                        {
                                                                            spec.count
                                                                        }
                                                                    </dt>
                                                                    <dd className="text-[11px] leading-tight font-medium text-maroon-900/70">
                                                                        {
                                                                            spec.label
                                                                        }
                                                                    </dd>
                                                                </div>
                                                            ),
                                                        )}
                                                    </dl>

                                                    <p className="mt-5 text-xs font-semibold tracking-widest text-maroon-900/50 uppercase">
                                                        Harga
                                                    </p>
                                                    <p className="text-xl font-extrabold text-maroon">
                                                        {type.price_label ??
                                                            'Hubungi CS'}
                                                    </p>

                                                    <div className="mt-5 flex flex-wrap gap-2">
                                                        {(type.brochure_url ??
                                                            project.brochure_url) && (
                                                            <a
                                                                href={
                                                                    type.brochure_url ??
                                                                    project.brochure_url!
                                                                }
                                                                target="_blank"
                                                                rel="noreferrer"
                                                                className="inline-flex min-h-11 cursor-pointer items-center gap-2 rounded-full bg-gold px-4 text-sm font-bold text-maroon-900 transition hover:bg-gold-dark active:scale-[0.98]"
                                                            >
                                                                <Download className="size-4" />{' '}
                                                                Download Brosur
                                                            </a>
                                                        )}
                                                        <a
                                                            href={wa(type.name)}
                                                            target="_blank"
                                                            rel="noreferrer"
                                                            className="inline-flex min-h-11 cursor-pointer items-center gap-2 rounded-full bg-wa px-4 text-sm font-bold text-white transition hover:bg-wa-dark active:scale-[0.98]"
                                                        >
                                                            <MessageCircle className="size-4" />{' '}
                                                            Whatsapp
                                                        </a>
                                                    </div>
                                                </div>
                                            </article>
                                        );
                                    })}
                                </div>
                            </div>
                        )}
                    </div>
                </section>
            )}

            {/* Update Terkini */}
            {updateVideo && (
                <section className="py-14 sm:py-16 lg:py-24">
                    <div className="mx-auto max-w-7xl px-4 lg:px-8">
                        <SectionHeading title="Update Terkini" />
                        <div className="mx-auto aspect-video max-w-4xl overflow-hidden rounded-2xl bg-maroon-900 shadow-xl">
                            <iframe
                                src={updateVideo}
                                title={`Update ${project.name}`}
                                allowFullScreen
                                loading="lazy"
                                className="size-full"
                            />
                        </div>
                    </div>
                </section>
            )}

            {/* Lokasi */}
            {(locationVideo || map || project.map_url) && (
                <section className="bg-maroon-50/60 py-14 sm:py-16 lg:py-24">
                    <div className="mx-auto max-w-7xl px-4 lg:px-8">
                        <SectionHeading title="Lokasi" />
                        {locationVideo ? (
                            <div className="mx-auto aspect-video max-w-4xl overflow-hidden rounded-2xl bg-maroon-900 shadow-xl">
                                <iframe
                                    src={locationVideo}
                                    title={`Lokasi ${project.name}`}
                                    allowFullScreen
                                    loading="lazy"
                                    className="size-full"
                                />
                            </div>
                        ) : (
                            map && (
                                <div className="mx-auto aspect-4/3 max-w-4xl overflow-hidden rounded-2xl border border-maroon-100 shadow-lg sm:aspect-video">
                                    <iframe
                                        src={map}
                                        title={`Peta ${project.name}`}
                                        loading="lazy"
                                        className="size-full"
                                    />
                                </div>
                            )
                        )}
                        {project.map_url && (
                            <div className="mt-8 text-center">
                                <a
                                    href={project.map_url}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="inline-flex min-h-12 cursor-pointer items-center gap-2 rounded-full bg-maroon px-6 font-bold text-white shadow-lg transition hover:bg-maroon-700 active:scale-[0.98]"
                                >
                                    <MapPin className="size-5" /> Buka di Google
                                    Maps
                                </a>
                            </div>
                        )}
                    </div>
                </section>
            )}

            {!hero && !sitePlan && gallery.length === 0 && (
                <section className="py-10">
                    <div className="mx-auto flex max-w-3xl items-center gap-3 rounded-xl border border-maroon-100 bg-maroon-50 px-5 py-4 text-sm text-maroon-900/70">
                        <Building2 className="size-5 shrink-0 text-maroon" />
                        Foto dan site plan perumahan ini belum diunggah lewat
                        CMS.
                    </div>
                </section>
            )}
        </SiteLayout>
    );
}
