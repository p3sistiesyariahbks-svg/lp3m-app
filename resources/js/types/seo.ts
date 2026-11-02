type HeadKey = { 'head-key': string };

export type SEOMeta = HeadKey &
  (
    | { name: string; property?: never; content: string }
    | { property: string; name?: never; content: string }
  );

export type SEOLink = HeadKey & {
  rel: string;
  href: string;
  hreflang?: string;
};

export type SEO = {
  title: string;
  meta: SEOMeta[];
  link: SEOLink[];
};
