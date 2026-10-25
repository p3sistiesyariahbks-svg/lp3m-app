export type MenuItem = {
  id: string;
  title: string;
  url: string;
  icon: string;
  classes: string;
  target: string;
  rel: string;
  active: boolean;
  children: MenuItem[];
};

export type Menu = {
  id: string;
  name: string;
  is_visible: boolean;
  menu_items: MenuItem[];
};

export type SocialLinks = {
  facebook: string | null;
  twitter: string | null;
  instagram: string | null;
  linkedin: string | null;
  youtube: string | null;
  github: string | null;
  discord: string | null;
};

export type PageProps = {
  menu: {
    header: Menu;
    footer: Menu;
  };
  socialLinks: SocialLinks;
  [key: string]: unknown;
};
