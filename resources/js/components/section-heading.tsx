export default function SectionHeading({
    title,
    subtitle,
    light = false,
}: {
    title: string;
    subtitle?: string | null;
    light?: boolean;
}) {
    return (
        <div className="mb-8 text-center sm:mb-10">
            <h2
                className={`text-2xl font-extrabold tracking-tight sm:text-3xl lg:text-4xl ${light ? 'text-white' : 'text-maroon-900'}`}
            >
                {title}
            </h2>
            {/* Curved underline, mirrors the reference site's swoosh. */}
            <svg
                viewBox="0 0 200 14"
                className={`mx-auto mt-3 h-3 w-44 ${light ? 'text-gold' : 'text-maroon'}`}
                fill="none"
                aria-hidden="true"
            >
                <path
                    d="M2 11C40 3 160 3 198 11"
                    stroke="currentColor"
                    strokeWidth="4"
                    strokeLinecap="round"
                />
            </svg>
            {subtitle && (
                <p
                    className={`mx-auto mt-4 max-w-2xl text-sm leading-relaxed sm:text-base ${light ? 'text-maroon-100' : 'text-maroon-900/70'}`}
                >
                    {subtitle}
                </p>
            )}
        </div>
    );
}
