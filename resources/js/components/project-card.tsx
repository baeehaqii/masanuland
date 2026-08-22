import { Link, usePage } from '@inertiajs/react';
import { ArrowRight, Building2, MapPin, MessageCircle } from 'lucide-react';

import { img, rupiah, waLink } from '@/lib/site';
import type { Project, SharedProps } from '@/types/site';

export default function ProjectCard({ project }: { project: Project }) {
    const { site } = usePage<SharedProps>().props;
    const cover = img(project.card_image ?? project.hero_image);

    return (
        <article className="group flex flex-col overflow-hidden rounded-2xl bg-maroon shadow-lg ring-1 ring-maroon-900/10 transition hover:-translate-y-1 hover:shadow-2xl">
            <div className="relative aspect-16/10 overflow-hidden bg-maroon-700">
                {cover ? (
                    <img
                        src={cover}
                        alt={project.name}
                        loading="lazy"
                        decoding="async"
                        className="size-full object-cover transition-transform duration-300 group-hover:scale-105"
                    />
                ) : (
                    <div className="grid size-full place-items-center text-maroon-200">
                        <Building2 className="size-10" />
                    </div>
                )}
                {project.badges?.[0] && (
                    <span className="absolute top-3 left-3 rounded-full bg-gold px-3 py-1 text-xs font-bold text-maroon-900">
                        {project.badges[0]}
                    </span>
                )}
            </div>

            <div className="flex flex-1 flex-col p-5 text-center text-white">
                <h3 className="text-xl font-extrabold">{project.name}</h3>
                {project.tagline && (
                    <p className="mt-1 text-sm text-maroon-100">
                        {project.tagline}
                    </p>
                )}
                {project.location && (
                    <p className="mt-1 flex items-center justify-center gap-1 text-xs text-maroon-200">
                        <MapPin className="size-3" /> {project.location}
                    </p>
                )}

                <div className="mt-4">
                    {project.price_before && (
                        <p className="text-sm text-gold line-through">
                            {rupiah(project.price_before)}
                        </p>
                    )}
                    <p className="text-xl font-extrabold">
                        {rupiah(project.price_from) ?? 'Hubungi CS'}
                    </p>
                    {project.price_note && (
                        <p className="mt-1 text-xs font-medium text-gold">
                            {project.price_note}
                        </p>
                    )}
                </div>

                <div className="mt-auto flex gap-2 pt-5">
                    <Link
                        href={`/perumahan/${project.slug}`}
                        className="inline-flex min-h-11 flex-1 cursor-pointer items-center justify-center gap-1.5 rounded-full bg-gold px-4 text-sm font-bold text-maroon-900 transition hover:bg-gold-dark active:scale-[0.98]"
                    >
                        {site.buttons?.detail_label ?? 'Lihat Detail'}{' '}
                        <ArrowRight className="size-4" />
                    </Link>
                    <a
                        href={waLink(
                            site,
                            `Halo ${site.brand_name}, mohon informasi ${project.name}. Saya dapat informasi dari WEBSITE.`,
                        )}
                        target="_blank"
                        rel="noreferrer"
                        className="inline-flex min-h-11 flex-1 cursor-pointer items-center justify-center gap-1.5 rounded-full bg-wa px-4 text-sm font-bold text-white transition hover:bg-wa-dark active:scale-[0.98]"
                    >
                        <MessageCircle className="size-4" /> Whatsapp
                    </a>
                </div>
            </div>
        </article>
    );
}
