import {
    Bath,
    BedDouble,
    Car,
    CookingPot,
    Download,
    House,
    Layers,
    MessageCircle,
    Sofa,
    Trees,
    UtensilsCrossed,
} from 'lucide-react';
import type { LucideIcon } from 'lucide-react';

import type { LightboxState } from '@/components/image-lightbox';
import { img, rupiah } from '@/lib/site';
import type { HouseType } from '@/types/site';

/** Ikon bawaan untuk spesifikasi yang belum diberi SVG, ditebak dari nama ruangnya. */
const specIcons: [RegExp, LucideIcon][] = [
    [/tidur|bed/i, BedDouble],
    [/mandi|wc|toilet/i, Bath],
    [/makan/i, UtensilsCrossed],
    [/dapur/i, CookingPot],
    [/tamu|keluarga/i, Sofa],
    [/carport|garasi|parkir/i, Car],
    [/taman/i, Trees],
    [/lantai/i, Layers],
];

export default function HouseTypeCard({
    type,
    brochureUrl,
    waHref,
    priceLabel,
    priceEmpty,
    onOpen,
}: {
    type: HouseType;
    brochureUrl: string | null;
    waHref: string;
    priceLabel: string;
    priceEmpty: string;
    onOpen: (state: LightboxState) => void;
}) {
    // Gambar utama + denah lainnya dibuka sebagai satu galeri di lightbox.
    const plans = [
        ...(type.image ? [{ image: type.image, label: type.name }] : []),
        ...(type.floor_plans ?? []),
    ]
        .map((plan) => ({ src: img(plan.image), label: plan.label }))
        .filter((plan): plan is { src: string; label: string } => !!plan.src);
    const open = (index: number) =>
        onOpen({
            images: plans.map((plan) => plan.src),
            captions: plans.map((plan) => plan.label),
            index,
            alt: type.name,
        });
    const extraFrom = type.image ? 1 : 0;

    return (
        <article className="flex flex-col overflow-hidden rounded-2xl border border-maroon-100 bg-white shadow-sm">
            <div className="aspect-16/10 bg-maroon-50">
                {plans[0] ? (
                    <button
                        type="button"
                        onClick={() => open(0)}
                        aria-label={`Perbesar ${plans[0].label}`}
                        className="block size-full cursor-zoom-in"
                    >
                        <img
                            src={plans[0].src}
                            alt={plans[0].label}
                            loading="lazy"
                            className="size-full object-cover"
                        />
                    </button>
                ) : (
                    <div className="grid size-full place-items-center text-maroon-200">
                        <House className="size-10" />
                    </div>
                )}
            </div>

            <div className="flex flex-1 flex-col p-5">
                <h3 className="text-xl font-extrabold text-maroon-900">
                    {type.name}
                </h3>

                {(type.specs ?? []).length > 0 && (
                    <ul className="mt-3 flex flex-wrap gap-2">
                        {type.specs!.map((spec) => {
                            const Icon =
                                specIcons.find(([match]) =>
                                    match.test(spec.label),
                                )?.[1] ?? House;

                            return (
                                <li
                                    key={spec.label}
                                    title={spec.label}
                                    className="inline-flex items-center gap-1.5 rounded-lg bg-maroon-50 px-2.5 py-1.5 text-sm font-bold text-maroon"
                                >
                                    {spec.icon ? (
                                        <img
                                            src={img(spec.icon)}
                                            alt=""
                                            className="size-4 object-contain"
                                        />
                                    ) : (
                                        <Icon className="size-4" />
                                    )}
                                    {spec.count}
                                    <span className="sr-only">
                                        {spec.label}
                                    </span>
                                </li>
                            );
                        })}
                    </ul>
                )}

                {plans.length > extraFrom && (
                    <div className="mt-3 flex flex-wrap gap-2">
                        {plans.slice(extraFrom).map((plan, i) => (
                            <button
                                key={plan.src}
                                type="button"
                                onClick={() => open(i + extraFrom)}
                                className="inline-flex min-h-9 cursor-pointer items-center gap-1.5 rounded-full border border-maroon-200 px-3 text-xs font-bold text-maroon-900 transition hover:border-maroon hover:bg-maroon-50"
                            >
                                <Layers className="size-3.5 text-maroon" />
                                {plan.label}
                            </button>
                        ))}
                    </div>
                )}

                <p className="mt-4 text-xs font-semibold tracking-widest text-maroon-900/50 uppercase">
                    {priceLabel}
                </p>
                <p className="text-lg font-extrabold text-maroon">
                    {rupiah(type.price) ?? priceEmpty}
                </p>

                <div className="mt-auto flex flex-wrap gap-2 pt-4">
                    {brochureUrl && (
                        <a
                            href={brochureUrl}
                            target="_blank"
                            rel="noreferrer"
                            className="inline-flex min-h-11 cursor-pointer items-center gap-2 rounded-full bg-gold px-4 text-sm font-bold text-maroon-900 transition hover:bg-gold-dark active:scale-[0.98]"
                        >
                            <Download className="size-4" /> Download Brosur
                        </a>
                    )}
                    <a
                        href={waHref}
                        target="_blank"
                        rel="noreferrer"
                        className="inline-flex min-h-11 cursor-pointer items-center gap-2 rounded-full bg-wa px-4 text-sm font-bold text-white transition hover:bg-wa-dark active:scale-[0.98]"
                    >
                        <MessageCircle className="size-4" /> Whatsapp
                    </a>
                </div>
            </div>
        </article>
    );
}
