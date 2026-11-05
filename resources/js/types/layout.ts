import type { GeneralSettings, SocialLinksSettings } from '@/types/settings';

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

export type PageProps = {
  menu: {
    header: Menu;
    footer: Menu;
  };
  settings: {
    general: GeneralSettings;
    socialLinks: SocialLinksSettings;
  };
  [key: string]: unknown;
};
