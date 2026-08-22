export type Stat = { value: string; label: string };
export type Social = { label: string; url: string };
export type Distance = { minutes: number | string; place: string };
export type Spec = { count: number | string; label: string };

export type MenuItem = {
    label: string;
    url: string | null;
    type: 'link' | 'projects' | 'brochure';
};

export type Reason = {
    icon: string | null;
    title: string;
    body: string | null;
};
export type NamePart = {
    word: string;
    origin: string | null;
    meaning: string | null;
    note: string | null;
};
export type Misi = { title: string; body: string | null };
export type Budaya = {
    letter: string;
    title: string;
    english: string | null;
    body: string | null;
};

export interface Buttons {
    whatsapp_label?: string | null;
    whatsapp_mobile_label?: string | null;
    brochure_label?: string | null;
    detail_label?: string | null;
    cta_title?: string | null;
    cta_label?: string | null;
    footer_menu_title?: string | null;
    footer_contact_title?: string | null;
    footer_social_title?: string | null;
    copyright?: string | null;
}

export interface PageHome {
    about_eyebrow?: string | null;
    about_title?: string | null;
    about_image?: string | null;
    about_link_label?: string | null;
    projects_title?: string | null;
    projects_subtitle?: string | null;
    why_title?: string | null;
    why_subtitle?: string | null;
    reasons?: Reason[] | null;
    map_title?: string | null;
    show_stats?: boolean | null;
    show_map?: boolean | null;
}

export interface PageAbout {
    hero_title?: string | null;
    hero_eyebrow?: string | null;
    eyebrow?: string | null;
    title?: string | null;
    name_title?: string | null;
    name_parts?: NamePart[] | null;
    name_conclusion?: string | null;
    visi_title?: string | null;
    visi?: string | null;
    misi_title?: string | null;
    misi?: Misi[] | null;
    budaya_title?: string | null;
    budaya_subtitle?: string | null;
    budaya?: Budaya[] | null;
    stats_title?: string | null;
}

export interface PageTestimonials {
    hero_title?: string | null;
    hero_subtitle?: string | null;
    empty_text?: string | null;
}

export interface Site {
    brand_name: string;
    logo: string | null;
    logo_footer: string | null;
    favicon: string | null;
    menu_header: MenuItem[] | null;
    menu_footer: MenuItem[] | null;
    buttons: Buttons | null;
    page_home: PageHome | null;
    page_about: PageAbout | null;
    page_testimonials: PageTestimonials | null;
    hero_image: string | null;
    hero_slides: string[] | null;
    hero_title: string | null;
    hero_subtitle: string | null;
    hero_badges: string[] | null;
    hero_note: string | null;
    tagline: string | null;
    about_text: string | null;
    about_points: string[] | null;
    about_video: string | null;
    stats: Stat[] | null;
    phone: string | null;
    whatsapp: string | null;
    whatsapp_text: string | null;
    email: string | null;
    address: string | null;
    socials: Social[] | null;
    map_embed: string | null;
    brochure_url: string | null;
}

export interface HouseType {
    id: number;
    name: string;
    image: string | null;
    price_label: string | null;
    specs: Spec[] | null;
    brochure_url: string | null;
}

export interface Project {
    id: number;
    name: string;
    slug: string;
    tagline: string | null;
    location: string | null;
    price_from: number | null;
    price_before: number | null;
    price_note: string | null;
    badges: string[] | null;
    distances: Distance[] | null;
    features: string[] | null;
    gallery: string[] | null;
    update_video: string | null;
    location_video: string | null;
    card_image: string | null;
    hero_image: string | null;
    site_plan_image: string | null;
    description: string | null;
    map_embed: string | null;
    map_url: string | null;
    brochure_url: string | null;
    house_types?: HouseType[];
}

export interface Testimonial {
    id: number;
    name: string;
    location: string | null;
    content: string | null;
    image: string | null;
    video_url: string | null;
    project?: { id: number; name: string } | null;
}

export interface SharedProps {
    site: Site;
    navProjects: { name: string; slug: string }[];
    [key: string]: unknown;
}
