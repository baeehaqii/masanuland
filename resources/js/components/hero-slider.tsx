import { ChevronLeft, ChevronRight } from 'lucide-react';
import { useCallback, useEffect, useRef, useState } from 'react';

/**
 * Promo banner slider that sits directly under the navigation.
 *
 * ponytail: native scroll-snap does the swiping, momentum and bounds — JS only
 * drives the dots, arrows and autoplay. No carousel dependency.
 */
export type Slide = { src: string; alt: string };

export default function HeroSlider({ slides }: { slides: Slide[] }) {
    const track = useRef<HTMLDivElement>(null);
    const [index, setIndex] = useState(0);
    const many = slides.length > 1;

    const go = useCallback(
        (to: number) => {
            const el = track.current;

            if (el) {
                el.scrollTo({
                    left:
                        ((to + slides.length) % slides.length) * el.clientWidth,
                    behavior: 'smooth',
                });
            }
        },
        [slides.length],
    );

    // Restarting on every index change means a manual swipe also resets the timer.
    useEffect(() => {
        if (
            !many ||
            window.matchMedia('(prefers-reduced-motion: reduce)').matches
        ) {
            return;
        }

        const timer = setTimeout(() => go(index + 1), 6000);

        return () => clearTimeout(timer);
    }, [go, index, many]);

    return (
        <section
            aria-roledescription="carousel"
            aria-label="Promo Masanuland"
            className="relative bg-brick"
        >
            <div
                ref={track}
                onScroll={(event) => {
                    const el = event.currentTarget;

                    setIndex(Math.round(el.scrollLeft / el.clientWidth));
                }}
                className="no-scrollbar flex snap-x snap-mandatory overflow-x-auto overscroll-x-contain"
            >
                {slides.map((slide, i) => (
                    <div key={i} className="w-full shrink-0 snap-center">
                        {/* object-contain: promo text is baked into the image, so never crop it. */}
                        <img
                            src={slide.src}
                            alt={slide.alt}
                            width={1920}
                            height={786}
                            loading={i === 0 ? 'eager' : 'lazy'}
                            fetchPriority={i === 0 ? 'high' : 'auto'}
                            decoding="async"
                            className="aspect-[1920/786] w-full object-contain"
                        />
                    </div>
                ))}
            </div>

            {many && (
                <>
                    {(
                        [
                            ['Sebelumnya', -1, 'left-2 sm:left-4', ChevronLeft],
                            [
                                'Berikutnya',
                                1,
                                'right-2 sm:right-4',
                                ChevronRight,
                            ],
                        ] as const
                    ).map(([label, step, side, Icon]) => (
                        <button
                            key={label}
                            type="button"
                            aria-label={label}
                            onClick={() => go(index + step)}
                            className={`absolute top-1/2 ${side} hidden size-11 -translate-y-1/2 cursor-pointer place-items-center rounded-full bg-white/85 text-maroon shadow-lg transition hover:bg-white active:scale-95 sm:grid`}
                        >
                            <Icon className="size-6" />
                        </button>
                    ))}

                    {/* Keeps the dots readable whatever the uploaded banner looks like. */}
                    <div
                        aria-hidden="true"
                        className="pointer-events-none absolute inset-x-0 bottom-0 h-14 bg-linear-to-t from-black/25 to-transparent"
                    />

                    <div className="absolute inset-x-0 bottom-0 flex justify-center">
                        {slides.map((_, i) => (
                            <button
                                key={i}
                                type="button"
                                aria-label={`Slide ${i + 1}`}
                                aria-current={i === index}
                                onClick={() => go(i)}
                                className="grid size-11 cursor-pointer place-items-center"
                            >
                                <span
                                    className={`h-2 rounded-full bg-white transition-all ${
                                        i === index
                                            ? 'w-6 opacity-100'
                                            : 'w-2 opacity-55'
                                    }`}
                                />
                            </button>
                        ))}
                    </div>
                </>
            )}
        </section>
    );
}
