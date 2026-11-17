export type GeneralSettings = {
  site_name: string;
  company_office?: string;
  latitude: number;
  longitude: number;
  support_email?: string;
  support_phone?: string;
  footer_page: string;
  site_logo?: string;
  favicon?: string;
  site_language: string;
  timezone: string;
  date_format: string;
};

export type SocialLinksSettings = {
  facebook?: string;
  twitter?: string;
  instagram?: string;
  linkedin?: string;
  youtube?: string;
  github?: string;
  discord?: string;
};

export type DefaultTheme = 'system' | 'light' | 'dark';

export type LayoutStyle = 'left' | 'right' | 'top' | 'bottom' | 'cover';

export type AppearanceSettings = {
  default_theme: DefaultTheme;
  brand_color: string;
  items_per_page: number;
  layout_style: LayoutStyle;
  media_path?: string;
  media_alt?: string;
  media_size: string;
  blur: number;
};
