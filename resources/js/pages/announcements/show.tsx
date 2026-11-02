import * as React from 'react';
import { cn } from 'cn';
import { Head, Link } from '@inertiajs/react';
import {
  CalendarIcon,
  CopyIcon,
  DotIcon,
  MailIcon,
  UserIcon,
  MegaphoneIcon,
} from 'lucide-react';
import {
  SiFacebook,
  SiLine,
  SiLinkedin,
  SiTelegram,
  SiThreads,
  SiWhatsapp,
  SiX,
} from '@icons-pack/react-simple-icons';
import {
  EmailShareButton,
  FacebookShareButton,
  LineShareButton,
  LinkedinShareButton,
  TelegramShareButton,
  ThreadsShareButton,
  WhatsappShareButton,
  XShareButton,
} from 'react-share';
import { useClipboard } from '@mantine/hooks';
import DOMPurify from 'isomorphic-dompurify';
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from '@/components/ui/breadcrumb';
import { Button, buttonVariants } from '@/components/ui/button';
import {
  Empty,
  EmptyDescription,
  EmptyHeader,
  EmptyMedia,
  EmptyTitle,
} from '@/components/ui/empty';
import { show as announcementShow } from '@/routes/announcements';
import type { Announcement } from '@/types/announcement';
import type { SEO } from '@/types/seo';

export default function AnnouncementsShowPage({
  seo,
  announcement,
}: {
  seo: SEO;
  announcement: Announcement;
}) {
  const content = DOMPurify.sanitize(announcement.content);

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

      <article className="bg-background border-b-0 py-12 lg:py-16">
        <div className="mx-auto max-w-(--breakpoint-xl) px-4 sm:px-6 lg:px-8">
          <Breadcrumb className="mb-8">
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
                <BreadcrumbPage>{announcement.title}</BreadcrumbPage>
              </BreadcrumbItem>
            </BreadcrumbList>
          </Breadcrumb>

          <div className="mx-auto max-w-3xl">
            <header className="w-full text-center">
              <h1 className="text-foreground mb-6 text-3xl font-semibold tracking-tight text-balance sm:text-4xl md:text-5xl md:tracking-tighter">
                {announcement.title}
              </h1>

              <div className="flex flex-wrap items-center justify-center gap-x-1 gap-y-2">
                <div className="text-muted-foreground flex items-center gap-1.5 text-sm">
                  <UserIcon className="h-4 w-4" /> {announcement.created_by}
                </div>

                <DotIcon className="text-muted-foreground" />

                <div className="text-muted-foreground flex items-center gap-1.5 text-sm">
                  <CalendarIcon className="size-4" />{' '}
                  {announcement.published_at}
                </div>
              </div>
            </header>

            <div className="mt-12 flex flex-col gap-6">
              <div
                className="prose prose-lg dark:prose-invert prose-a:text-brand prose-img:rounded-lg max-w-none"
                dangerouslySetInnerHTML={{ __html: content }}
              />
            </div>

            <div className="mt-8 border-t pt-6">
              <p className="text-foreground mb-4 text-sm font-medium">
                Share this announcement:
              </p>

              <ShareButtons announcement={announcement} />
            </div>
          </div>
        </div>
      </article>
    </React.Fragment>
  );
}

function ShareButtons({ announcement }: { announcement: Announcement }) {
  const clipboard = useClipboard({ timeout: 500 });

  const shareButtons = [
    {
      key: 'email',
      label: 'Email',
      ShareButton: EmailShareButton,
      Icon: MailIcon,
      subject: announcement.title,
      body: '',
    },
    {
      key: 'facebook',
      label: 'Facebook',
      ShareButton: FacebookShareButton,
      Icon: SiFacebook,
    },
    {
      key: 'line',
      label: 'Line',
      ShareButton: LineShareButton,
      Icon: SiLine,
      text: announcement.title,
    },
    {
      key: 'linkedin',
      label: 'LinkedIn',
      ShareButton: LinkedinShareButton,
      Icon: SiLinkedin,
    },
    {
      key: 'telegram',
      label: 'Telegram',
      ShareButton: TelegramShareButton,
      Icon: SiTelegram,
      text: announcement.title,
    },
    {
      key: 'threads',
      label: 'Threads',
      ShareButton: ThreadsShareButton,
      Icon: SiThreads,
      text: announcement.title,
    },
    {
      key: 'whatsapp',
      label: 'WhatsApp',
      ShareButton: WhatsappShareButton,
      Icon: SiWhatsapp,
      message: announcement.title,
      separator: ':: ',
    },
    {
      key: 'twitter',
      label: 'Twitter/X',
      ShareButton: XShareButton,
      Icon: SiX,
      text: announcement.title,
    },
  ] as const;

  return (
    <div className="flex flex-wrap gap-2">
      {shareButtons.map(({ key, label, ShareButton, Icon, ...props }) => (
        <ShareButton
          key={key}
          className={cn(buttonVariants({ size: 'lg' }), 'w-auto')}
          url={announcementShow.url(announcement.slug)}
          resetButtonStyle={false}
          {...props}
        >
          <Icon data-icon="inline-start" /> {label}
        </ShareButton>
      ))}

      <Button
        variant="outline"
        size="lg"
        onClick={() => clipboard.copy(announcementShow.url(announcement.slug))}
      >
        {clipboard.copied ? (
          'Copied!'
        ) : (
          <React.Fragment>
            <CopyIcon data-icon="inline-start" /> Copy Link
          </React.Fragment>
        )}
      </Button>
    </div>
  );
}

export function AnnouncementEmptyState() {
  return (
    <Empty className="mt-0 border border-solid">
      <EmptyHeader>
        <EmptyMedia variant="icon">
          <MegaphoneIcon />
        </EmptyMedia>
        <EmptyTitle>No announcements found</EmptyTitle>
        <EmptyDescription>
          There are no announcements to show here yet.
        </EmptyDescription>
      </EmptyHeader>
    </Empty>
  );
}
