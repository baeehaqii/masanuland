import { Head, Link, usePage } from '@inertiajs/react';
import {
    ArrowRight,
    BadgeCheck,
    CalendarDays,
    ChevronRight,
    Clock,
    HandCoins,
    House,
    Landmark,
    MapPin,
    Navigation,
    ShieldCheck,
} from 'lucide-react';

import HeroSlider from '@/components/hero-slider';
import type { Slide } from '@/components/hero-slider';
import ProjectCard from '@/components/project-card';
import SectionHeading from '@/components/section-heading';
import SiteLayout from '@/components/site-layout';
import { img, mapSrc } from '@/lib/site';
import type { Project, SharedProps } from '@/types/site';

const statIcons = [House, Navigation, Landmark, CalendarDays];

/** Icon keys offered by /admin → Halaman → Beranda. */
const reasonIcons = {
    shield: ShieldCheck,
    badge: BadgeCheck,
    coins: HandCoins,
    home: House,
    map: MapPin,
    clock: Clock,
};

export default function Home({ projects }: { projects: Project[] }) {
    const { site } = usePage<SharedProps>().props;
    const page = site.page_home ?? {};
    const aboutImage = img(page.about_image);
    const map = mapSrc(site.map_embed);

    // Slides come from /admin → Halaman → Beranda; a lone hero image stands in.
    const slides: Slide[] = (
        site.hero_slides?.length ? site.hero_slides : [site.hero_image]
    )
        .map((path, i) => ({
            src: img(path),
            alt: `Promo ${site.brand_name} ${i + 1}`,
        }))
        .filter((slide): slide is Slide => Boolean(slide.src));

    return (
        <SiteLayout>
            <Head title={`${site.brand_name} — Perumahan Banyumas`} />

            {slides.length > 0 && <HeroSlider slides={slides} />}

            {/* About */}
            <section className="py-14 sm:py-16 lg:py-24">
                <div className="mx-auto grid max-w-7xl items-center gap-10 px-4 lg:grid-cols-2 lg:gap-14 lg:px-8">
                    <div>
                        {page.about_eyebrow && (
                            <p className="text-xs font-bold tracking-widest text-maroon uppercase">
                                {page.about_eyebrow}
                            </p>
                        )}
                        <h1 className="mt-3 text-2xl font-extrabold tracking-tight text-maroon-900 sm:text-3xl lg:text-4xl">
                            {page.about_title ?? site.brand_name}
                        </h1>
                        <p className="mt-4 leading-relaxed text-maroon-900/75 sm:text-justify">
                            {site.about_text}
                        </p>

                        <ul className="mt-7 grid gap-x-6 gap-y-4 sm:grid-cols-2">
                            {(site.about_points ?? []).map((point) => (
                                <li
                                    key={point}
                                    className="flex items-start gap-2 font-bold text-maroon-900"
                                >
                                    <ChevronRight
                                        className="mt-0.5 size-5 shrink-0 text-maroon"
                                        strokeWidth={3}
                                    />
                                    {point}
                                </li>
                            ))}
                        </ul>

                        <Link
                            href="/tentang-kami"
                            className="mt-8 inline-flex min-h-11 cursor-pointer items-center gap-2 rounded-full border-2 border-maroon px-5 font-bold text-maroon transition hover:bg-maroon hover:text-white"
                        >
                            {page.about_link_label ?? 'Selengkapnya'}{' '}
                            <ArrowRight className="size-4" />
                        </Link>
                    </div>

                    {aboutImage && (
                        <div className="mx-auto w-full max-w-md overflow-hidden rounded-3xl shadow-xl ring-1 ring-maroon-100 lg:max-w-lg">
                            <img
                                src={aboutImage}
                                alt={`Fasad rumah ${site.brand_name}`}
                                width={900}
                                height={900}
                                loading="lazy"
                                decoding="async"
                                className="aspect-square w-full object-cover"
                            />
                        </div>
                    )}
                </div>
            </section>

            {/* Stats */}
            {page.show_stats !== false && (site.stats ?? []).length > 0 && (
                <section className="bg-maroon-100/40 py-14 sm:py-16 lg:py-20">
                    <div className="mx-auto grid max-w-7xl grid-cols-2 gap-x-4 gap-y-10 px-4 lg:grid-cols-4 lg:px-8">
                        {(site.stats ?? []).map((stat, index) => {
                            const Icon = statIcons[index % statIcons.length];

                            return (
                                <div key={stat.label} className="text-center">
                                    <span className="mx-auto grid size-24 place-items-center rounded-3xl bg-white shadow-md sm:size-28 lg:size-32">
                                        <Icon
                                            className="size-11 text-maroon sm:size-13 lg:size-15"
                                            strokeWidth={2.25}
                                        />
                                    </span>
                                    <p className="mt-5 text-3xl font-extrabold text-maroon-900 sm:text-4xl lg:text-5xl">
                                        {stat.value}
                                    </p>
                                    <p className="mt-1 text-sm font-semibold text-maroon-900/60 sm:text-base">
                                        {stat.label}
                                    </p>
                                </div>
                            );
                        })}
                    </div>
                </section>
            )}

            {/* Projects */}
            <section
                id="perumahan"
                className="scroll-mt-20 py-14 sm:py-16 lg:py-24"
            >
                <div className="mx-auto max-w-7xl px-4 lg:px-8">
                    <SectionHeading
                        title={page.projects_title ?? 'Lokasi Unggulan'}
                        subtitle={page.projects_subtitle ?? undefined}
                    />
                    {projects.length === 0 ? (
                        <p className="text-center text-maroon-900/60">
                            {page.projects_empty ??
                                'Perumahan akan segera ditampilkan.'}
                        </p>
                    ) : (
                        <div className="grid gap-5 sm:grid-cols-2 sm:gap-6 lg:grid-cols-3">
                            {projects.map((project) => (
                                <ProjectCard
                                    key={project.id}
                                    project={project}
                                />
                            ))}
                        </div>
                    )}
                </div>
            </section>

            {/* Why us */}
            {(page.reasons ?? []).length > 0 && (
                <section className="bg-maroon-50/60 py-14 sm:py-16 lg:py-24">
                    <div className="mx-auto max-w-7xl px-4 lg:px-8">
                        <SectionHeading
                            title={page.why_title ?? 'Developer Terpercaya'}
                            subtitle={page.why_subtitle ?? undefined}
                        />
                        <div className="grid gap-5 sm:grid-cols-3 sm:gap-6">
                            {(page.reasons ?? []).map((item) => {
                                const Icon =
                                    reasonIcons[
                                        (item.icon ??
                                            'shield') as keyof typeof reasonIcons
                                    ] ?? ShieldCheck;

                                return (
                                    <div
                                        key={item.title}
                                        className="rounded-2xl border border-maroon-100 bg-white p-6 shadow-sm transition hover:border-maroon-200 hover:shadow-md"
                                    >
                                        <span className="grid size-12 place-items-center rounded-xl bg-maroon-50 text-maroon">
                                            <Icon className="size-6" />
                                        </span>
                                        <h3 className="mt-4 text-lg font-extrabold text-maroon-900">
                                            {item.title}
                                        </h3>
                                        <p className="mt-2 text-sm leading-relaxed text-maroon-900/70">
                                            {item.body}
                                        </p>
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                </section>
            )}

            {/* Map */}
            {page.show_map !== false && map && (
                <section className="py-14 sm:py-16 lg:py-24">
                    <div className="mx-auto max-w-7xl px-4 lg:px-8">
                        <SectionHeading
                            title={page.map_title ?? 'Map Lokasi'}
                        />
                        <div className="aspect-4/3 overflow-hidden rounded-2xl border border-maroon-100 shadow-lg sm:aspect-16/9">
                            <iframe
                                src={map}
                                title="Peta lokasi"
                                loading="lazy"
                                className="size-full"
                            />
                        </div>
                    </div>
                </section>
            )}
        </SiteLayout>
    );
}
