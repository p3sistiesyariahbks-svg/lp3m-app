import * as React from 'react';
import { Head } from '@inertiajs/react';
import { MapPinIcon } from 'lucide-react';
import {
  Empty,
  EmptyDescription,
  EmptyHeader,
  EmptyMedia,
  EmptyTitle,
} from '@/components/ui/empty';

import type { SEO } from '@/types/seo';

export default function PlacementsListPage({ seo }: { seo: SEO }) {
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
            <h1 className="mb-4 text-4xl font-bold tracking-tight">
              Placements
            </h1>

            <p className="text-muted-foreground mt-0 text-lg">
              Explore KKM, internship, and K2P placements.
            </p>
          </div>
        </div>
      </section>

      <section className="bg-background py-12 lg:py-16">
        <div className="mx-auto flex max-w-(--breakpoint-xl) flex-col items-start gap-12 px-4 py-10 sm:px-6 lg:flex-row lg:px-8 lg:py-16">
          <div className="m-0 block h-auto w-full p-0">
            <div className="flex items-center justify-between gap-4">
              <h2 className="truncate text-[1.5rem] font-semibold tracking-tight">
                Placements
              </h2>
            </div>

            <PlacementEmptyState />
          </div>
        </div>
      </section>
    </React.Fragment>
  );
}

export function PlacementEmptyState() {
  return (
    <Empty className="mt-4 border border-solid">
      <EmptyHeader>
        <EmptyMedia variant="icon">
          <MapPinIcon />
        </EmptyMedia>
        <EmptyTitle>No placements found</EmptyTitle>
        <EmptyDescription>
          There are no placements to show here yet.
        </EmptyDescription>
      </EmptyHeader>
    </Empty>
  );
}
