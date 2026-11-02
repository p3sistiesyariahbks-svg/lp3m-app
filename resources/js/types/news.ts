import type { Category } from '@/types/category';
import type { Media } from '@/types/media';
import type { Paginated } from '@/types/pagination';
import type { SEO } from '@/types/seo';

export type StatusType = 'draft' | 'published' | 'scheduled';

export type News = {
  id: string;
  thumbnail: Media;
  title: string;
  slug: string;
  content: string;
  description: string;
  reading_time: string;
  status: StatusType;
  categories: Category[];
  published_at: string;
  created_by: string;
};

export type Filters = {
  sort_by: string;
};

export type PageProps = {
  seo: SEO;
  news: Paginated<News>;
  categories: Category[];
  filters: Filters;
  [key: string]: unknown;
};
