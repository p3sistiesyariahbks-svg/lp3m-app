export type PaginationLink = {
  active: boolean;
  label: string;
  page: number;
  url: string;
};

export type Paginated<T> = {
  data: T[];
  links: {
    first: string;
    last: string;
    prev: string;
    next: string;
  };
  meta: {
    current_page: number;
    from: number;
    last_page: number;
    links: PaginationLink[];
    path: string;
    per_page: number;
    to: number;
    total: number;
  };
};
