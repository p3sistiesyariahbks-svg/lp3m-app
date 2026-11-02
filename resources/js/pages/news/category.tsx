import * as React from 'react';
import { Head, Link, InfiniteScroll, router, usePage } from '@inertiajs/react';
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from '@/components/ui/breadcrumb';
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import {
  CategoryItem,
  NewsCard,
  NewsEmptyState,
  sorts,
} from '@/pages/news/list';
import { index as newsIndex, category as newsCategory } from '@/routes/news';
import type { News } from '@/types/news';
import type { PageProps } from '@/types/category';

export default function NewsCategoryPage() {
  const {
    seo,
    items: news,
    category,
    categories,
    filters,
  } = usePage<PageProps<News>>().props;

  function handleSortBy(value: string | null) {
    router.get(
      newsCategory(category.slug),
      { sort_by: value },
      {
        only: ['items', 'filters'],
        preserveState: true,
        preserveScroll: true,
        preserveUrl: true,
        replace: true,
        reset: ['items'],
      },
    );
  }

  return (
    <React.Fragment>
      <Head title={seo.title}>
        {seo.meta.map((meta) => (
          <meta key={meta['head-key']} {...meta} />
        ))}
        {seo.link.map((link) => (
          <link
            key={link['head-key']}
            head-key={link['head-key']}
            rel={link.rel}
            href={link.href}
            {...(link.hreflang ? { hrefLang: link.hreflang } : {})}
          />
        ))}
      </Head>

      <section className="bg-muted border-b py-12 lg:py-16">
        <div className="mx-auto max-w-(--breakpoint-xl) px-4 sm:px-6 lg:px-8">
          <Breadcrumb className="mb-6">
            <BreadcrumbList>
              <BreadcrumbItem>
                <BreadcrumbLink
                  render={
                    <Link href="/" className="relative">
                      Home
                    </Link>
                  }
                />
              </BreadcrumbItem>
              <BreadcrumbSeparator />
              <BreadcrumbItem>
                <BreadcrumbLink
                  render={
                    <Link href={newsIndex()} className="relative">
                      News
                    </Link>
                  }
                />
              </BreadcrumbItem>
              <BreadcrumbSeparator />
              <BreadcrumbItem>
                <BreadcrumbPage>{category.name}</BreadcrumbPage>
              </BreadcrumbItem>
            </BreadcrumbList>
          </Breadcrumb>

          <div className="mx-auto max-w-3xl text-center">
            <span className="text-muted-foreground mb-2 block text-sm font-medium tracking-wide uppercase">
              Category
            </span>

            <h1 className="mb-4 text-4xl font-bold tracking-tight">
              {category.name}
            </h1>

            <p className="text-muted-foreground mt-0 text-lg">
              {category.description}
            </p>

            <p className="text-muted-foreground mt-4 text-sm">
              {news.meta.total} news
            </p>
          </div>
        </div>
      </section>

      <section className="bg-background py-12 lg:py-16">
        <div className="mx-auto flex max-w-(--breakpoint-xl) flex-col items-start gap-12 px-4 py-10 sm:px-6 lg:flex-row lg:px-8 lg:py-16">
          <div className="m-0 block h-auto w-full p-0">
            <div className="flex items-center justify-between gap-4">
              <h2 className="truncate text-[1.5rem] font-semibold tracking-tight">
                News
              </h2>

              {news.data.length > 0 && (
                <Select
                  items={sorts}
                  value={filters.sort_by || sorts[0].value}
                  onValueChange={handleSortBy}
                >
                  <SelectTrigger className="w-full max-w-[180px]">
                    <SelectValue placeholder="Sort by" />
                  </SelectTrigger>
                  <SelectContent alignItemWithTrigger={false}>
                    <SelectGroup>
                      <SelectLabel>Sort By</SelectLabel>
                      {sorts.map((sort) => (
                        <SelectItem key={sort.value} value={sort.value}>
                          {sort.label}
                        </SelectItem>
                      ))}
                    </SelectGroup>
                  </SelectContent>
                </Select>
              )}
            </div>

            {news.data.length > 0 ? (
              <InfiniteScroll
                data="items"
                preserveUrl
                onlyNext
                manualAfter={3}
                next={({ loading, fetch, hasMore }) =>
                  hasMore && (
                    <div className="mt-16 flex items-center justify-between gap-4">
                      <Button
                        className="mx-auto"
                        size="lg"
                        variant="secondary"
                        onClick={fetch}
                        disabled={loading}
                      >
                        {loading && <Spinner data-icon="inline-start" />}
                        Load more news
                      </Button>
                    </div>
                  )
                }
              >
                <div className="mt-4 grid auto-rows-fr gap-8 md:grid-cols-2 lg:grid-cols-3">
                  {news.data.map((news) => (
                    <NewsCard key={news.id} news={news} />
                  ))}
                </div>
              </InfiniteScroll>
            ) : (
              <NewsEmptyState />
            )}
          </div>

          {categories.length > 0 && (
            <aside className="sticky top-24 w-full shrink-0 lg:max-w-80">
              <Card className="gap-4 p-6">
                <CardHeader className="p-0">
                  <CardTitle className="text-xl font-medium tracking-tight">
                    Categories
                  </CardTitle>
                </CardHeader>

                <CardContent className="p-0">
                  <ul className="mt-0 space-y-2">
                    {categories.map((category) => (
                      <li key={category.id}>
                        <CategoryItem category={category} />
                      </li>
                    ))}
                  </ul>
                </CardContent>
              </Card>
            </aside>
          )}
        </div>
      </section>
    </React.Fragment>
  );
}
