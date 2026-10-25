import * as React from 'react';
import { cn } from 'cn';
import { Head, Link } from '@inertiajs/react';
import { ExternalLink, MailIcon, MapPinIcon, PhoneIcon } from 'lucide-react';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { AspectRatio } from '@/components/ui/aspect-ratio';
import {
  Map,
  MapControls,
  MapMarker,
  MarkerContent,
  MarkerLabel,
  MarkerPopup,
  MarkerTooltip,
} from '@/components/ui/map';
import { buttonVariants } from '@/components/ui/button';
import type { ContactInfo } from '@/types/contact';

const contactItems: ContactInfo[] = [
  {
    icon: MailIcon,
    title: 'Email',
    href: 'mailto:akashmoradiya3444@gmail.com',
    label: 'akashmoradiya3444@gmail.com',
  },
  {
    icon: MapPinIcon,
    title: 'Office',
    href: 'https://maps.app.goo.gl/NRkm4KwmghLdgtTm6',
    label: (
      <>
        100 Smith Street Collingwood <br /> VIC 3066 AU
      </>
    ),
  },
  {
    icon: PhoneIcon,
    title: 'Phone',
    href: 'tel:+15550000000',
    label: '+1 (555) 000-0000',
  },
];

export default function ContactPage() {
  return (
    <React.Fragment>
      <Head title="Contact" />

      <section className="bg-muted border-b py-12 lg:py-16">
        <div className="mx-auto max-w-(--breakpoint-xl) px-4 sm:px-6 lg:px-8">
          <div className="mx-auto max-w-3xl text-center">
            <h2 className="mb-4 text-4xl font-bold tracking-tight">Contact</h2>

            <p className="text-muted-foreground mt-0 text-lg">
              Our friendly team is always here to chat
            </p>
          </div>
        </div>
      </section>

      <section className="bg-background py-12 lg:py-16">
        <div className="mx-auto flex max-w-(--breakpoint-lg) flex-col items-start gap-12 px-4 py-10 sm:px-6 lg:flex-row lg:px-8 lg:py-16">
          <div className="m-0 block h-auto w-full p-0">
            <div className="mt-0 grid auto-rows-fr gap-8 md:grid-cols-3 lg:grid-cols-3">
              {contactItems.map((item) => (
                <ContactInfoItem key={item.title} item={item} />
              ))}
            </div>

            <div className="mt-8 flex items-center justify-between gap-4">
              <MapInfoLocation />
            </div>
          </div>
        </div>
      </section>
    </React.Fragment>
  );
}

function MapInfoLocation() {
  const coordinate = [102.14967581349075, 1.466922671632213] as [
    number,
    number,
  ];

  return (
    <Card className="relative z-0 w-full p-4">
      <AspectRatio
        ratio={16 / 9}
        className="relative w-full overflow-hidden border-none"
      >
        <Map
          className="size-full!"
          center={coordinate}
          zoom={12}
          styles={{
            light: 'https://tiles.openfreemap.org/styles/liberty',
            dark: 'https://tiles.openfreemap.org/styles/dark',
          }}
        >
          <MapControls
            position="top-right"
            showZoom
            showCompass
            showLocate
            showFullscreen
          />

          <MapMarker longitude={coordinate[0]} latitude={coordinate[1]}>
            <MarkerContent>
              <MapPinIcon />
              <MarkerLabel className="font-bold" position="bottom">
                HQ Office
              </MarkerLabel>
            </MarkerContent>

            <MarkerTooltip>Click me</MarkerTooltip>

            <MarkerPopup className="w-56 p-0" closeButton>
              <Card className="gap-0 overflow-hidden rounded-lg py-0 shadow-none">
                <CardHeader className="relative p-0">
                  <AspectRatio
                    ratio={16 / 9}
                    className="relative w-full overflow-hidden border-b"
                  >
                    <img
                      className="bg-muted size-full object-cover"
                      sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
                      src="https://images.unsplash.com/photo-1534430480872-3498386e7856?w=300&h=200&fit=crop"
                      alt="HQ Office"
                    />
                  </AspectRatio>
                </CardHeader>

                <CardContent className="flex flex-col p-2">
                  <h3 className="mt-0 text-sm text-xl font-medium tracking-[-0.02em]">
                    HQ Office
                  </h3>

                  <p className="text-muted-foreground mt-2 text-xs">
                    100 Smith Street Collingwood <br /> VIC 3066 AU
                  </p>

                  <Link
                    href="https://maps.app.goo.gl/NRkm4KwmghLdgtTm6"
                    className={cn(
                      buttonVariants({ size: 'sm' }),
                      'mt-4 w-full shadow-none',
                    )}
                    target="_blank"
                  >
                    Open Google Maps <ExternalLink data-icon="inline-end" />
                  </Link>
                </CardContent>
              </Card>
            </MarkerPopup>
          </MapMarker>
        </Map>
      </AspectRatio>
    </Card>
  );
}

function ContactInfoItem({ item }: { item: ContactInfo }) {
  return (
    <div className="bg-muted/30 flex size-full flex-row items-center gap-4 rounded-xl border p-6 text-left md:flex-col md:text-center">
      <div className="border-border/30 bg-primary/5 text-primary dark:bg-primary/10 flex h-12 w-12 items-center justify-center rounded-full border shadow-xl/2">
        <item.icon />
      </div>

      <div className="min-w-0 flex-1 space-y-2">
        <h3 className="text-lg font-medium">{item.title}</h3>

        <a
          href={item.href}
          className="text-muted-foreground break-all"
          target="_blank"
        >
          {item.label}
        </a>
      </div>
    </div>
  );
}
