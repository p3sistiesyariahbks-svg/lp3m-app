import * as React from 'react';
import { useSnapCarousel } from 'react-snap-carousel';
import { cn } from 'cn';
import { Head, Link, InfiniteScroll, router, usePage } from '@inertiajs/react';
import {
  CalendarIcon,
  DotIcon,
  UserIcon,
  ImagesIcon,
  TagIcon,
} from 'lucide-react';
import { useInterval, useReducedMotion } from '@mantine/hooks';
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
  index as photoIndex,
  category as photoCategory,
  show as photoShow,
} from '@/routes/gallery/photos';
import type { Gallery, PageProps } from '@/types/gallery';
import type { Category } from '@/types/category';

export const sorts = [
  { label: 'Latest', value: 'latest' },
  { label: 'Oldest', value: 'oldest' },
  { label: 'Title (A-Z)', value: 'title_asc' },
  { label: 'Title (Z-A)', value: 'title_desc' },
  { label: 'Most Viewed', value: 'popular' },
] as const;

export default function GalleryPhotoListPage() {
  const { seo, galleries, categories, filters } = usePage<PageProps>().props;

  function handleSortBy(value: string | null) {
    router.get(
      photoIndex(),
      { sort_by: value },
      {
        only: ['galleries', 'filters'],
        preserveState: true,
        preserveScroll: true,
        preserveUrl: true,
        replace: true,
        reset: ['galleries'],
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
            <h1 className="mb-4 text-4xl font-bold tracking-tight">Photos</h1>

            <p className="text-muted-foreground mt-0 text-lg">
              Documentation of research and community service activities
            </p>
          </div>
        </div>
      </section>

      <section className="bg-background py-12 lg:py-16">
        <div className="mx-auto flex max-w-(--breakpoint-xl) flex-col items-start gap-12 px-4 py-10 sm:px-6 lg:flex-row lg:px-8 lg:py-16">
          <div className="m-0 block h-auto w-full p-0">
            <div className="flex items-center justify-between gap-4">
              <h2 className="truncate text-[1.5rem] font-semibold tracking-tight">
                Photos
              </h2>

              {galleries.data.length > 0 && (
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

            {galleries.data.length > 0 ? (
              <InfiniteScroll
                data="galleries"
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
                        Load more photos
                      </Button>
                    </div>
                  )
                }
              >
                <div className="mt-4 grid auto-rows-fr gap-8 md:grid-cols-2 lg:grid-cols-3">
                  {galleries.data.map((gallery) => (
                    <GalleryCard key={gallery.id} gallery={gallery} />
                  ))}
                </div>
              </InfiniteScroll>
            ) : (
              <GalleryEmptyState />
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

export function GalleryCard({ gallery }: { gallery: Gallery }) {
  const {
    scrollRef,
    activePageIndex,
    pages,
    hasNextPage,
    next,
    goTo,
    refresh,
  } = useSnapCarousel({
    initialPages: gallery.images.map((_, index) => [index]),
  });

  const reducedMotion = useReducedMotion();

  const interval = useInterval(() => {
    if (hasNextPage) {
      next();
    } else {
      goTo(0, { behavior: 'smooth' });
    }
  }, 3000);

  function handlePointerEnter(event: React.PointerEvent<HTMLAnchorElement>) {
    if (
      event.pointerType === 'mouse' &&
      gallery.images.length > 1 &&
      !reducedMotion
    ) {
      interval.start();
    }
  }

  return (
    <Card className="gap-0 overflow-hidden rounded-lg py-0 shadow-none transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
      <CardHeader className="relative p-0">
        <Link
          href={photoShow(gallery.slug)}
          className="relative"
          onPointerEnter={handlePointerEnter}
          onPointerLeave={interval.stop}
        >
          <AspectRatio
            ratio={16 / 9}
            className="relative w-full overflow-hidden border-b"
          >
            <div
              ref={scrollRef}
              className="flex size-full snap-x snap-mandatory overflow-hidden"
            >
              {gallery.images.map((photo) => (
                <div
                  key={photo.id}
                  className="h-full min-w-0 shrink-0 grow-0 basis-full snap-start overflow-hidden"
                >
                  <img
                    className="bg-muted size-full object-cover transition-transform duration-500 ease-in-out group-hover/card:scale-110"
                    sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
                    src={photo.image.url}
                    alt={photo.alt}
                    onLoad={refresh}
                  />
                </div>
              ))}
            </div>

            <span className="pointer-events-none absolute right-3 bottom-3 rounded-full bg-black/60 px-2.5 py-1 text-xs font-medium text-white tabular-nums">
              {pages.length > 0 ? activePageIndex + 1 : 0} / {pages.length}
            </span>
          </AspectRatio>
        </Link>
      </CardHeader>

      <CardContent className="flex flex-col p-4">
        <div className="flex flex-wrap items-center gap-2">
          {gallery.categories.map((category) => (
            <Badge
              key={category.id}
              render={
                <Link href={photoCategory(category.slug)} className="relative">
                  {category.name}
                </Link>
              }
            />
          ))}
        </div>

        <h3 className="mt-4 line-clamp-2 text-[1.5rem] font-medium tracking-tight text-pretty">
          <Link href={photoShow(gallery.slug)} className="relative">
            {gallery.title}
          </Link>
        </h3>

        <p className="text-muted-foreground mt-2 line-clamp-3 text-ellipsis">
          {gallery.description}
        </p>

        <div className="mt-4 flex items-center justify-start gap-1">
          <span className="text-muted-foreground flex items-center gap-1.5 text-sm">
            <UserIcon className="h-4 w-4" /> {gallery.created_by}
          </span>

          <DotIcon className="text-muted-foreground" />

          <span className="text-muted-foreground flex items-center gap-1.5 text-sm">
            <CalendarIcon className="size-4" /> {gallery.published_at}
          </span>
        </div>
      </CardContent>
    </Card>
  );
}

export function GalleryEmptyState() {
  return (
    <Empty className="mt-4 border border-solid">
      <EmptyHeader>
        <EmptyMedia variant="icon">
          <ImagesIcon />
        </EmptyMedia>
        <EmptyTitle>No photos found</EmptyTitle>
        <EmptyDescription>
          There are no photos to show here yet.
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
            photoCategory(category.slug).url,
          ),
        })}
        render={
          <Link href={photoCategory(category.slug)} preserveScroll>
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
