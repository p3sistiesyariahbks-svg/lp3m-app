import type { Paginated } from '@/types/pagination';
import type { SEO } from '@/types/seo';

export type Category = {
  id: string;
  name: string;
  description: string;
  slug: string;
  children: Category[];
};

export type Filters = {
  sort_by: string;
};

export type PageProps<T> = {
  seo: SEO;
  items: Paginated<T>;
  category: Category;
  categories: Category[];
  filters: Filters;
  [key: string]: unknown;
};
