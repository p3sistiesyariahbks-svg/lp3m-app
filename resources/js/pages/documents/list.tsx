import * as React from 'react';
import { cn } from 'cn';
import { Head, Link, InfiniteScroll, router, usePage } from '@inertiajs/react';
import {
  CalendarIcon,
  DotIcon,
  FileTextIcon,
  UserIcon,
  TagIcon,
} from 'lucide-react';
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
import { Item, ItemContent, ItemMedia, ItemTitle } from '@/components/ui/item';
import { AspectRatio } from '@/components/ui/aspect-ratio';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import {
  Empty,
  EmptyDescription,
  EmptyHeader,
  EmptyMedia,
  EmptyTitle,
} from '@/components/ui/empty';
import {
  index as documentIndex,
  category as documentCategory,
  show as documentShow,
} from '@/routes/documents';
import type { Document, PageProps } from '@/types/document';
import type { Category } from '@/types/category';

export const sorts = [
  { label: 'Latest', value: 'latest' },
  { label: 'Oldest', value: 'oldest' },
  { label: 'Title (A-Z)', value: 'title_asc' },
  { label: 'Title (Z-A)', value: 'title_desc' },
  { label: 'Most Viewed', value: 'popular' },
] as const;

export default function DocumentListPage() {
  const { seo, documents, categories, filters } = usePage<PageProps>().props;

  function handleSortBy(value: string | null) {
    router.get(
      documentIndex(),
      { sort_by: value },
      {
        only: ['documents', 'filters'],
        preserveState: true,
        preserveScroll: true,
        preserveUrl: true,
        replace: true,
        reset: ['documents'],
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
          <div className="mx-auto max-w-3xl text-center">
            <h1 className="mb-4 text-4xl font-bold tracking-tight">Document</h1>

            <p className="text-muted-foreground mt-0 text-lg">
              Find reports, forms, and resources all in one place
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
                data="documents"
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

export function DocumentCard({ document }: { document: Document }) {
  return (
    <div className="group flex w-full flex-col text-start transition-all duration-300 hover:-translate-y-1 hover:shadow-none">
      <Link href={documentShow(document.slug)} className="relative">
        <AspectRatio
          ratio={4 / 5}
          className="relative mb-0 w-full overflow-hidden rounded-xl"
        >
          <img
            className="bg-muted size-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-110"
            sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
            src={document.thumbnail.url}
            alt={document.title}
          />
        </AspectRatio>
      </Link>

      <div className="px-1">
        <div className="mt-4 flex flex-wrap items-center gap-2">
          {document.categories.map((category) => (
            <Badge
              key={category.id}
              render={
                <Link
                  href={documentCategory(category.slug)}
                  className="relative"
                >
                  {category.name}
                </Link>
              }
            />
          ))}
        </div>

        <h3 className="mt-3 line-clamp-2 text-[1.35rem] font-medium tracking-[-0.015em] text-pretty">
          <Link href={documentShow(document.slug)} className="relative">
            {document.title}
          </Link>
        </h3>

        <div className="mt-4 flex items-center justify-start gap-1">
          <span className="text-muted-foreground flex items-center gap-1.5 text-sm">
            <UserIcon className="h-4 w-4" /> {document.created_by}
          </span>

          <DotIcon className="text-muted-foreground" />

          <span className="text-muted-foreground flex items-center gap-1.5 text-sm">
            <CalendarIcon className="size-4" /> {document.published_at}
          </span>
        </div>
      </div>
    </div>
  );
}

export function DocumentEmptyState() {
  return (
    <Empty className="mt-4 border border-solid">
      <EmptyHeader>
        <EmptyMedia variant="icon">
          <FileTextIcon />
        </EmptyMedia>
        <EmptyTitle>No documents found</EmptyTitle>
        <EmptyDescription>
          There are no documents to show here yet.
        </EmptyDescription>
      </EmptyHeader>
    </Empty>
  );
}

export function CategoryItem({ category }: { category: Category }) {
  const { url } = usePage();

  return (
    <React.Fragment>
      <Item
        variant="default"
        size="xs"
        className={cn({
          'bg-muted/50 hover:bg-muted focus:bg-muted': url.startsWith(
            documentCategory(category.slug).url,
          ),
        })}
        render={
          <Link href={documentCategory(category.slug)} preserveScroll>
            <ItemMedia variant="icon">
              <TagIcon />
            </ItemMedia>

            <ItemContent>
              <ItemTitle>{category.name}</ItemTitle>
            </ItemContent>
          </Link>
        }
      />

      {category.children.length > 0 && (
        <ul className="mt-2 ml-2 space-y-2 pl-2">
          {category.children.map((grandchild) => (
            <li key={grandchild.id}>
              <CategoryItem category={grandchild} />
            </li>
          ))}
        </ul>
      )}
    </React.Fragment>
  );
}
