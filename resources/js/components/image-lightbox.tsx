import { ChevronLeft, ChevronRight, X } from 'lucide-react';
import { useEffect, useRef } from 'react';

export type LightboxState = {
    images: string[];
    index: number;
    alt: string;
    captions?: string[];
};

/**
 * Gambar ukuran penuh di atas latar blur. Pakai <dialog> bawaan browser:
 * Escape, fokus, dan scroll-lock sudah diurus browser.
 */
export default function ImageLightbox({
    state,
    onChange,
}: {
    state: LightboxState | null;
    onChange: (state: LightboxState | null) => void;
}) {
    const dialog = useRef<HTMLDialogElement>(null);

    useEffect(() => {
        if (state && !dialog.current?.open) {
            dialog.current?.showModal();
        }

        if (!state && dialog.current?.open) {
            dialog.current.close();
        }
    }, [state]);

    const count = state?.images.length ?? 0;
    const go = (step: number) =>
        state &&
        onChange({ ...state, index: (state.index + step + count) % count });

    return (
        <dialog
            ref={dialog}
            onClose={() => onChange(null)}
            onClick={(event) =>
                event.target === event.currentTarget && onChange(null)
            }
            onKeyDown={(event) => {
                if (event.key === 'ArrowRight') {
                    go(1);
                }

                if (event.key === 'ArrowLeft') {
                    go(-1);
                }
            }}
            className="m-auto max-h-none max-w-none bg-transparent p-0 backdrop:bg-maroon-900/50 backdrop:backdrop-blur-md"
        >
            {state && (
                <div className="relative flex items-center justify-center">
                    <img
                        src={state.images[state.index]}
                        alt={state.alt}
                        className="max-h-[90dvh] max-w-[94vw] rounded-2xl bg-white object-contain shadow-2xl"
                    />

                    {state.captions?.[state.index] && (
                        <p className="absolute top-3 left-3 rounded-full bg-maroon-900/80 px-4 py-2 text-sm font-bold text-white">
                            {state.captions[state.index]}
                        </p>
                    )}

                    <button
                        type="button"
                        onClick={() => onChange(null)}
                        aria-label="Tutup"
                        className="absolute top-3 right-3 grid size-11 cursor-pointer place-items-center rounded-full bg-white/90 text-maroon-900 shadow-lg transition hover:bg-white"
                    >
                        <X className="size-5" />
                    </button>

                    {count > 1 && (
                        <>
                            <button
                                type="button"
                                onClick={() => go(-1)}
                                aria-label="Gambar sebelumnya"
                                className="absolute left-3 grid size-11 cursor-pointer place-items-center rounded-full bg-white/90 text-maroon-900 shadow-lg transition hover:bg-white"
                            >
                                <ChevronLeft className="size-5" />
                            </button>
                            <button
                                type="button"
                                onClick={() => go(1)}
                                aria-label="Gambar berikutnya"
                                className="absolute right-3 grid size-11 cursor-pointer place-items-center rounded-full bg-white/90 text-maroon-900 shadow-lg transition hover:bg-white"
                            >
                                <ChevronRight className="size-5" />
                            </button>
                            <p className="absolute bottom-3 rounded-full bg-maroon-900/70 px-3 py-1 text-xs font-semibold text-white">
                                {state.index + 1} / {count}
                            </p>
                        </>
                    )}
                </div>
            )}
        </dialog>
    );
}
