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
import { Card } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import {
  CategoryItem,
  DocumentCard,
  DocumentEmptyState,
  sorts,
} from '@/pages/documents/list';
import {
  index as documentIndex,
  category as documentCategory,
} from '@/routes/documents';
import type { Document } from '@/types/document';
import type { PageProps } from '@/types/category';

export default function DocumentCategoryPage() {
  const {
    items: documents,
    category,
    categories,
    filters,
  } = usePage<PageProps<Document>>().props;

  function handleSortBy(value: string | null) {
    router.get(
      documentCategory(category.slug),
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
      <Head title="Document" />

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
                    <Link href={documentIndex()} className="relative">
                      Document
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
              {documents.meta.total} documents
            </p>
          </div>
        </div>
      </section>

      <section className="bg-background py-12 lg:py-16">
        <div className="mx-auto flex max-w-(--breakpoint-xl) flex-col items-start gap-12 px-4 py-10 sm:px-6 lg:flex-row lg:px-8 lg:py-16">
          <div className="m-0 block h-auto w-full p-0">
            <div className="flex items-center justify-between gap-4">
              <h2 className="truncate text-[1.5rem] font-semibold tracking-tight">
                Documents
              </h2>

              {documents.data.length > 0 && (
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

            {documents.data.length > 0 ? (
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
                        Load more documents
                      </Button>
                    </div>
                  )
                }
              >
                <div className="mt-4 grid auto-rows-fr gap-8 md:grid-cols-2 lg:grid-cols-3">
                  {documents.data.map((document) => (
                    <DocumentCard key={document.id} document={document} />
                  ))}
                </div>
              </InfiniteScroll>
            ) : (
              <DocumentEmptyState />
            )}
          </div>

          {categories.length > 0 && (
            <aside className="sticky top-8 w-full shrink-0 lg:max-w-80">
              <Card className="gap-4 p-6">
                <h3 className="text-xl font-medium tracking-tight">
                  Categories
                </h3>

                <ul className="mt-0 space-y-1">
                  {categories.map((category) => (
                    <li key={category.id}>
                      <CategoryItem category={category} />
                    </li>
                  ))}
                </ul>
              </Card>
            </aside>
          )}
        </div>
      </section>
    </React.Fragment>
  );
}
