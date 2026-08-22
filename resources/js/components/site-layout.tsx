import { Link, usePage } from '@inertiajs/react';
import {
    ChevronDown,
    Mail,
    MapPin,
    Menu,
    MessageCircle,
    Phone,
    X,
} from 'lucide-react';
import { useState } from 'react';
import type { ReactNode } from 'react';

import SocialIcon, { hasSocialIcon } from '@/components/social-icons';
import { img, waLink } from '@/lib/site';
import type { MenuItem, SharedProps } from '@/types/site';

/** Only used if someone empties the menu in /admin → Menu. */
const fallbackMenu: MenuItem[] = [
    { label: 'Home', url: '/', type: 'link' },
    { label: 'Tentang Kami', url: '/tentang-kami', type: 'link' },
    { label: 'Perumahan', url: '/#perumahan', type: 'projects' },
];

function useSite() {
    return usePage<SharedProps>().props;
}

function Logo({ dark = false }: { dark?: boolean }) {
    const { site } = useSite();
    const logo = img(dark ? (site.logo_footer ?? site.logo) : site.logo);

    if (logo) {
        return <img src={logo} alt={site.brand_name} className="h-10 w-auto" />;
    }

    return (
        <span className="flex items-center gap-2">
            <span className="grid size-9 place-items-center rounded-lg bg-maroon font-extrabold text-white">
                {site.brand_name.charAt(0)}
            </span>
            <span
                className={`text-xl leading-none font-extrabold tracking-tight ${dark ? 'text-white' : 'text-maroon'}`}
            >
                {site.brand_name}
            </span>
        </span>
    );
}

function Header() {
    const page = usePage<SharedProps>();
    const { site, navProjects } = page.props;
    const current = page.url;
    const [open, setOpen] = useState(false);
    const [openDropdown, setOpenDropdown] = useState<string | null>(null);

    const items = site.menu_header?.length ? site.menu_header : fallbackMenu;
    const brochure =
        site.brochure_url ??
        waLink(site, 'Halo, mohon informasi brosur & harga.');

    const hrefOf = (item: MenuItem) =>
        item.type === 'brochure' ? brochure : (item.url ?? '#');

    const isActive = (item: MenuItem) => {
        const href = item.url ?? '';

        if (item.type === 'projects') {
            return current.startsWith('/perumahan');
        }

        return href === '/'
            ? current === '/'
            : Boolean(href) &&
                  current.startsWith(href.split('#')[0]) &&
                  href !== '/';
    };

    const navClass = (active: boolean) =>
        `inline-flex min-h-11 cursor-pointer items-center gap-1 rounded-full px-4 text-sm font-semibold transition ${
            active
                ? 'bg-maroon text-white'
                : 'text-maroon-900 hover:bg-maroon-50'
        }`;

    return (
        <header
            onKeyDown={(event) => {
                if (event.key === 'Escape') {
                    setOpen(false);
                    setOpenDropdown(null);
                }
            }}
            className="sticky top-0 z-40 border-b border-maroon-100 bg-white/95 backdrop-blur"
        >
            <div className="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 lg:px-8">
                <Link
                    href="/"
                    aria-label={site.brand_name}
                    className="shrink-0"
                >
                    <Logo />
                </Link>

                <nav
                    aria-label="Menu utama"
                    className="hidden items-center gap-1 lg:flex"
                >
                    {items.map((item, index) => {
                        const key = `${item.label}-${index}`;

                        // Auto dropdown: the project list comes from the Perumahan resource.
                        if (item.type === 'projects') {
                            return (
                                <div
                                    key={key}
                                    className="relative"
                                    onMouseEnter={() => setOpenDropdown(key)}
                                    onMouseLeave={() => setOpenDropdown(null)}
                                    onBlur={(event) => {
                                        if (
                                            !event.currentTarget.contains(
                                                event.relatedTarget as Node,
                                            )
                                        ) {
                                            setOpenDropdown(null);
                                        }
                                    }}
                                >
                                    <button
                                        type="button"
                                        aria-expanded={openDropdown === key}
                                        onClick={() =>
                                            setOpenDropdown(
                                                openDropdown === key
                                                    ? null
                                                    : key,
                                            )
                                        }
                                        className={navClass(isActive(item))}
                                    >
                                        {item.label}
                                        <ChevronDown
                                            className={`size-4 transition-transform ${openDropdown === key ? 'rotate-180' : ''}`}
                                        />
                                    </button>
                                    {openDropdown === key && (
                                        <div className="absolute top-full left-0 w-64 rounded-xl border border-maroon-100 bg-white p-2 shadow-xl">
                                            {navProjects.map((project) => (
                                                <Link
                                                    key={project.slug}
                                                    href={`/perumahan/${project.slug}`}
                                                    onClick={() =>
                                                        setOpenDropdown(null)
                                                    }
                                                    className="flex min-h-11 items-center rounded-lg px-3 text-sm font-medium text-maroon-900 hover:bg-maroon-50"
                                                >
                                                    {project.name}
                                                </Link>
                                            ))}
                                        </div>
                                    )}
                                </div>
                            );
                        }

                        if (
                            item.type === 'brochure' ||
                            /^https?:|^#/.test(item.url ?? '')
                        ) {
                            return (
                                <a
                                    key={key}
                                    href={hrefOf(item)}
                                    target={
                                        item.type === 'brochure'
                                            ? '_blank'
                                            : undefined
                                    }
                                    rel="noreferrer"
                                    className={navClass(false)}
                                >
                                    {item.label}
                                </a>
                            );
                        }

                        return (
                            <Link
                                key={key}
                                href={hrefOf(item)}
                                aria-current={
                                    isActive(item) ? 'page' : undefined
                                }
                                className={navClass(isActive(item))}
                            >
                                {item.label}
                            </Link>
                        );
                    })}
                </nav>

                <div className="flex items-center gap-2">
                    <a
                        href={waLink(site)}
                        target="_blank"
                        rel="noreferrer"
                        className="hidden min-h-11 cursor-pointer items-center gap-2 rounded-full bg-wa px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-wa-dark sm:inline-flex"
                    >
                        <MessageCircle className="size-4" />{' '}
                        {site.buttons?.whatsapp_label ?? 'WhatsApp'}
                    </a>
                    <button
                        type="button"
                        aria-label={open ? 'Tutup menu' : 'Buka menu'}
                        aria-expanded={open}
                        onClick={() => setOpen((value) => !value)}
                        className="grid size-11 cursor-pointer place-items-center rounded-lg text-maroon transition hover:bg-maroon-50 lg:hidden"
                    >
                        {open ? (
                            <X className="size-6" />
                        ) : (
                            <Menu className="size-6" />
                        )}
                    </button>
                </div>
            </div>

            {open && (
                <nav
                    aria-label="Menu utama"
                    className="max-h-[calc(100svh-4rem)] overflow-y-auto border-t border-maroon-100 bg-white px-4 pt-2 pb-4 lg:hidden"
                >
                    {items.map((item, index) => {
                        const key = `${item.label}-${index}`;

                        if (item.type === 'projects') {
                            return (
                                <div key={key}>
                                    <p className="px-3 pt-4 pb-1 text-xs font-bold tracking-wider text-maroon-400 uppercase">
                                        {item.label}
                                    </p>
                                    {navProjects.map((project) => (
                                        <Link
                                            key={project.slug}
                                            href={`/perumahan/${project.slug}`}
                                            onClick={() => setOpen(false)}
                                            className="flex min-h-12 items-center rounded-xl px-3 text-sm font-medium text-maroon-900 transition hover:bg-maroon-50"
                                        >
                                            {project.name}
                                        </Link>
                                    ))}
                                </div>
                            );
                        }

                        if (
                            item.type === 'brochure' ||
                            /^https?:|^#/.test(item.url ?? '')
                        ) {
                            return (
                                <a
                                    key={key}
                                    href={hrefOf(item)}
                                    target={
                                        item.type === 'brochure'
                                            ? '_blank'
                                            : undefined
                                    }
                                    rel="noreferrer"
                                    className="flex min-h-12 items-center rounded-xl px-3 font-semibold text-maroon-900 transition hover:bg-maroon-50"
                                >
                                    {item.label}
                                </a>
                            );
                        }

                        return (
                            <Link
                                key={key}
                                href={hrefOf(item)}
                                onClick={() => setOpen(false)}
                                aria-current={
                                    isActive(item) ? 'page' : undefined
                                }
                                className={`flex min-h-12 items-center rounded-xl px-3 font-semibold transition ${
                                    isActive(item)
                                        ? 'bg-maroon text-white'
                                        : 'text-maroon-900 hover:bg-maroon-50'
                                }`}
                            >
                                {item.label}
                            </Link>
                        );
                    })}

                    <a
                        href={waLink(site)}
                        target="_blank"
                        rel="noreferrer"
                        className="mt-3 flex min-h-12 items-center justify-center gap-2 rounded-full bg-wa font-bold text-white shadow-sm transition hover:bg-wa-dark sm:hidden"
                    >
                        <MessageCircle className="size-5" />{' '}
                        {site.buttons?.whatsapp_mobile_label ??
                            'Hubungi via WhatsApp'}
                    </a>
                </nav>
            )}
        </header>
    );
}

export function CtaBand() {
    const { site } = useSite();

    return (
        <section className="bg-maroon">
            <div className="mx-auto flex max-w-7xl flex-col items-center justify-between gap-5 px-4 py-10 text-center sm:flex-row sm:text-left lg:px-8">
                <h2 className="text-xl font-extrabold tracking-tight text-white uppercase underline decoration-gold decoration-4 underline-offset-8 sm:text-3xl">
                    {site.buttons?.cta_title ?? 'Info Lebih Lanjut, Klik'}
                </h2>
                <a
                    href={waLink(site)}
                    target="_blank"
                    rel="noreferrer"
                    className="inline-flex min-h-12 cursor-pointer items-center gap-2 rounded-full bg-white px-6 font-bold text-maroon shadow-lg transition hover:bg-maroon-50 active:scale-[0.98]"
                >
                    <MessageCircle className="size-5" />{' '}
                    {site.buttons?.cta_label ?? site.phone ?? 'Hubungi Kami'}
                </a>
            </div>
        </section>
    );
}

function Footer() {
    const { site, navProjects } = useSite();
    const items = site.menu_footer?.length ? site.menu_footer : fallbackMenu;
    const brochure =
        site.brochure_url ??
        waLink(site, 'Halo, mohon informasi brosur & harga.');

    return (
        <footer className="bg-maroon-800 text-maroon-100">
            <div className="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:grid-cols-2 lg:grid-cols-4 lg:px-8">
                <div className="space-y-4 lg:col-span-2">
                    <Logo dark />
                    <p className="max-w-md text-sm leading-relaxed text-maroon-200">
                        {site.tagline}
                    </p>
                    {site.address && (
                        <p className="flex items-start gap-2 text-sm">
                            <MapPin className="mt-0.5 size-4 shrink-0 text-gold" />{' '}
                            {site.address}
                        </p>
                    )}
                </div>

                <div>
                    <h3 className="mb-4 text-xs font-bold tracking-widest text-white uppercase">
                        {site.buttons?.footer_menu_title ?? 'Menu'}
                    </h3>
                    <ul className="text-sm">
                        {items.flatMap((item, index) => {
                            if (item.type === 'projects') {
                                return navProjects.map((project) => (
                                    <li key={project.slug}>
                                        <Link
                                            href={`/perumahan/${project.slug}`}
                                            className="flex min-h-11 items-center hover:text-white"
                                        >
                                            {project.name}
                                        </Link>
                                    </li>
                                ));
                            }

                            const key = `${item.label}-${index}`;
                            const external =
                                item.type === 'brochure' ||
                                /^https?:|^#/.test(item.url ?? '');

                            return [
                                <li key={key}>
                                    {external ? (
                                        <a
                                            href={
                                                item.type === 'brochure'
                                                    ? brochure
                                                    : (item.url ?? '#')
                                            }
                                            target={
                                                item.type === 'brochure'
                                                    ? '_blank'
                                                    : undefined
                                            }
                                            rel="noreferrer"
                                            className="flex min-h-11 items-center hover:text-white"
                                        >
                                            {item.label}
                                        </a>
                                    ) : (
                                        <Link
                                            href={item.url ?? '#'}
                                            className="flex min-h-11 items-center hover:text-white"
                                        >
                                            {item.label}
                                        </Link>
                                    )}
                                </li>,
                            ];
                        })}
                    </ul>
                </div>

                <div>
                    <h3 className="mb-4 text-xs font-bold tracking-widest text-white uppercase">
                        {site.buttons?.footer_contact_title ?? 'Kontak Kami'}
                    </h3>
                    <ul className="space-y-3 text-sm">
                        {site.phone && (
                            <li>
                                <a
                                    href={waLink(site)}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="flex min-h-11 items-center gap-2 hover:text-white"
                                >
                                    <Phone className="size-4 text-gold" />{' '}
                                    {site.phone}
                                </a>
                            </li>
                        )}
                        {site.email && (
                            <li>
                                <a
                                    href={`mailto:${site.email}`}
                                    className="flex min-h-11 items-center gap-2 hover:text-white"
                                >
                                    <Mail className="size-4 text-gold" />{' '}
                                    {site.email}
                                </a>
                            </li>
                        )}
                    </ul>

                    <h3 className="mt-6 mb-3 text-xs font-bold tracking-widest text-white uppercase">
                        {site.buttons?.footer_social_title ?? 'Ikuti Kami'}
                    </h3>
                    <div className="flex gap-2">
                        {(site.socials ?? []).map((social) => (
                            <a
                                key={social.url}
                                href={social.url}
                                target="_blank"
                                rel="noreferrer"
                                aria-label={social.label}
                                title={social.label}
                                className="grid size-11 place-items-center rounded-full bg-maroon-900 text-white transition hover:bg-gold"
                            >
                                {hasSocialIcon(social.label) ? (
                                    <SocialIcon
                                        label={social.label}
                                        className="size-4"
                                    />
                                ) : (
                                    <span className="text-xs font-bold">
                                        {social.label.charAt(0)}
                                    </span>
                                )}
                            </a>
                        ))}
                    </div>
                </div>
            </div>

            <div className="border-t border-maroon-900 px-4 py-5 text-center text-xs text-maroon-200">
                {site.buttons?.copyright ??
                    `Copyright © ${new Date().getFullYear()} ${site.brand_name}`}
            </div>
        </footer>
    );
}

export default function SiteLayout({ children }: { children: ReactNode }) {
    const { site } = useSite();

    return (
        <div className="flex min-h-screen flex-col bg-white text-maroon-900">
            <Header />
            <main className="flex-1">{children}</main>
            <CtaBand />
            <Footer />

            <a
                href={waLink(site)}
                target="_blank"
                rel="noreferrer"
                aria-label="Hubungi via WhatsApp"
                className="fixed right-4 bottom-[max(1rem,env(safe-area-inset-bottom))] z-50 grid size-14 cursor-pointer place-items-center rounded-full bg-wa text-white shadow-xl transition hover:bg-wa-dark active:scale-95 sm:right-5"
            >
                <MessageCircle className="size-7" />
            </a>
        </div>
    );
}
