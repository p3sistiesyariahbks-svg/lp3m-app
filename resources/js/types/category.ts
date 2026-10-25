import type { Paginated } from '@/types/pagination';

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
  items: Paginated<T>;
  category: Category;
  categories: Category[];
  filters: Filters;
  [key: string]: unknown;
};
