import { Head, usePage } from '@inertiajs/react';
import { Quote, User } from 'lucide-react';

import SiteLayout from '@/components/site-layout';
import { embedUrl, img } from '@/lib/site';
import type { SharedProps, Testimonial } from '@/types/site';

export default function Testimonials({
    testimonials,
}: {
    testimonials: Testimonial[];
}) {
    const { site } = usePage<SharedProps>().props;
    const page = site.page_testimonials ?? {};

    return (
        <SiteLayout>
            <Head
                title={`${page.hero_title ?? 'Testimoni'} — ${site.brand_name}`}
            />

            <section className="bg-maroon py-12 text-center sm:py-16 lg:py-20">
                <div className="mx-auto max-w-3xl px-4">
                    <h1 className="text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-5xl">
                        {page.hero_title ?? 'Testimoni'}
                    </h1>
                    <p className="mt-4 text-maroon-100">
                        {page.hero_subtitle ??
                            `Cerita penghuni yang sudah menempati hunian ${site.brand_name}.`}
                    </p>
                </div>
            </section>

            <section className="py-14 sm:py-16 lg:py-24">
                <div className="mx-auto max-w-7xl px-4 lg:px-8">
                    {testimonials.length === 0 ? (
                        <p className="text-center text-maroon-900/60">
                            {page.empty_text ??
                                'Testimoni akan segera ditampilkan.'}
                        </p>
                    ) : (
                        <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            {testimonials.map((item) => {
                                const video = embedUrl(item.video_url);
                                const photo = img(item.image);

                                return (
                                    <article
                                        key={item.id}
                                        className="flex flex-col overflow-hidden rounded-2xl border border-maroon-100 bg-white shadow-sm"
                                    >
                                        {video && (
                                            <div className="aspect-video bg-maroon-900">
                                                <iframe
                                                    src={video}
                                                    title={item.name}
                                                    allowFullScreen
                                                    className="size-full"
                                                />
                                            </div>
                                        )}
                                        <div className="flex flex-1 flex-col p-6">
                                            <Quote className="size-7 text-maroon-200" />
                                            <p className="mt-3 flex-1 text-sm leading-relaxed text-maroon-900/80">
                                                {item.content}
                                            </p>
                                            <div className="mt-5 flex items-center gap-3 border-t border-maroon-100 pt-4">
                                                {photo ? (
                                                    <img
                                                        src={photo}
                                                        alt={item.name}
                                                        className="size-10 rounded-full object-cover"
                                                    />
                                                ) : (
                                                    <span className="grid size-10 place-items-center rounded-full bg-maroon-50 text-maroon">
                                                        <User className="size-5" />
                                                    </span>
                                                )}
                                                <div>
                                                    <p className="text-sm font-bold text-maroon-900">
                                                        {item.name}
                                                    </p>
                                                    <p className="text-xs text-maroon-900/60">
                                                        {item.location ??
                                                            item.project?.name}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                );
                            })}
                        </div>
                    )}
                </div>
            </section>
        </SiteLayout>
    );
}
