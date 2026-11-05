import * as React from 'react';
import { cn } from 'cn';
import { useTheme } from 'next-themes';
import { Head, Link, usePage } from '@inertiajs/react';
import { ChevronRightIcon, SunIcon, MoonIcon, MenuIcon } from 'lucide-react';
import {
  SiFacebook,
  SiX,
  SiInstagram,
  SiLinkedin,
  SiYoutube,
  SiGithub,
  SiDiscord,
} from '@icons-pack/react-simple-icons';
import DOMPurify from 'isomorphic-dompurify';
import { NavigationMenu as NavigationMenuPrimitive } from '@base-ui/react/navigation-menu';
import {
  NavigationMenu,
  NavigationMenuContent,
  NavigationMenuItem,
  NavigationMenuLink,
  NavigationMenuList,
  NavigationMenuTrigger,
  NavigationMenuPositioner,
  navigationMenuTriggerStyle,
} from '@/components/ui/navigation-menu';
import { AspectRatio } from '@/components/ui/aspect-ratio';
import { ScrollArea } from '@/components/ui/scroll-area';
import {
  Collapsible,
  CollapsibleContent,
  CollapsibleTrigger,
} from '@/components/ui/collapsible';
import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from '@/components/ui/accordion';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuGroup,
  DropdownMenuLabel,
  DropdownMenuRadioGroup,
  DropdownMenuRadioItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Button, buttonVariants } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { login } from '@/routes/filament/admin/auth';
import type { MenuItem, PageProps } from '@/types/layout';
import type { GeneralSettings, SocialLinksSettings } from '@/types/settings';
import { Media, mediaStyle } from '@/lib/media';

export default function MainLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  const {
    menu: { header, footer },
    settings: { general, socialLinks },
  } = usePage<PageProps>().props;

  const footerPage = DOMPurify.sanitize(general.footer_page);

  return (
    <React.Fragment>
      <Head>
        <style type="text/css">{mediaStyle}</style>
      </Head>

      <div className="relative m-0 flex min-h-screen w-full flex-col p-0">
        <header className="sticky top-0 left-0 z-10 m-0 block h-auto w-full p-0">
          <nav className="bg-background border-b">
            <Collapsible>
              <div className="mx-auto flex h-16 max-w-(--breakpoint-xl) items-center justify-between px-4 sm:px-6 lg:px-8">
                <div className="flex items-center gap-12">
                  <Logo general={general} />

                  <Media greaterThanOrEqual="md">
                    <NavigationMenu className="hidden md:block">
                      <NavigationMenuList>
                        {header?.menu_items.map((item) => (
                          <HeaderMenuItem key={item.id} item={item} />
                        ))}
                      </NavigationMenuList>
                    </NavigationMenu>
                  </Media>
                </div>

                <div className="flex items-center gap-3">
                  <ModeToggle />

                  <Media greaterThanOrEqual="md">
                    <Link
                      href={login()}
                      className={cn(buttonVariants(), 'w-auto')}
                      target="_blank"
                    >
                      Sign In
                    </Link>
                  </Media>

                  <Media lessThan="md">
                    <CollapsibleTrigger
                      render={
                        <Button variant="outline" size="icon">
                          <MenuIcon className="h-[1.2rem] w-[1.2rem]" />
                          <span className="sr-only">Open menu</span>
                        </Button>
                      }
                    />
                  </Media>
                </div>
              </div>

              <Media lessThan="md">
                <CollapsibleContent className="mx-auto flex max-w-(--breakpoint-xl) flex-col items-center gap-4 p-4 sm:px-6 lg:px-8">
                  <div className="m-0 block h-auto w-full p-0">
                    {header?.menu_items.map((item) => (
                      <HeaderAccordionItem key={item.id} item={item} />
                    ))}
                  </div>

                  <Separator orientation="horizontal" />

                  <Link
                    href={login()}
                    className={cn(buttonVariants({ size: 'lg' }), 'w-full')}
                    target="_blank"
                  >
                    Sign In
                  </Link>
                </CollapsibleContent>
              </Media>
            </Collapsible>
          </nav>
        </header>

        <main className="m-0 block h-auto w-auto shrink-0 grow p-0">
          <div className="mx-auto block h-auto w-full p-0">{children}</div>
        </main>

        <footer className="bg-muted m-0 block h-auto w-auto shrink-0 border-t p-0">
          <div className="mx-auto grid max-w-(--breakpoint-xl) grid-cols-2 gap-x-8 gap-y-10 px-4 py-12 sm:grid-cols-3 sm:px-6 md:grid-cols-4 lg:grid-cols-5 lg:px-8 xl:grid-cols-6">
            <div className="col-span-full xl:col-span-2">
              <Logo general={general} />

              <div
                className="prose prose-sm dark:prose-invert prose-a:text-brand prose-img:rounded-lg mt-4 max-w-none"
                dangerouslySetInnerHTML={{ __html: footerPage }}
              />
            </div>

            {footer?.menu_items.map((item) => (
              <FooterMenuItem key={item.id} item={item} />
            ))}
          </div>

          <Separator orientation="horizontal" />

          <div className="mx-auto flex max-w-(--breakpoint-xl) flex-col-reverse items-center justify-between gap-x-2 gap-y-5 px-4 py-8 sm:flex-row sm:px-6 lg:px-8">
            <span className="text-muted-foreground">
              &copy; {new Date().getFullYear()} Shadcn UI Blocks. All rights
              reserved.
            </span>

            <SocialLinks socialLinks={socialLinks} />
          </div>
        </footer>
      </div>
    </React.Fragment>
  );
}

function HeaderMenuItem({ item }: { item: MenuItem }) {
  if (item.children.length === 0) {
    return (
      <NavigationMenuItem>
        {item.url ? (
          <NavigationMenuLink
            className={cn(navigationMenuTriggerStyle(), item.classes)}
            target={item.target}
            rel={item.rel}
            active={item.active}
            render={
              <Link href={item.url} className="flex-row items-center gap-2">
                {item.title}
              </Link>
            }
          />
        ) : (
          <span
            className={cn(
              navigationMenuTriggerStyle(),
              'flex-row items-center gap-2',
              item.classes,
            )}
          >
            {item.title}
          </span>
        )}
      </NavigationMenuItem>
    );
  }

  return (
    <NavigationMenuItem>
      <NavigationMenuTrigger
        className={cn({
          'bg-muted/50 hover:bg-muted focus:bg-muted': item.active,
        })}
      >
        {item.title}
      </NavigationMenuTrigger>
      <NavigationMenuContent className="flex max-h-(--available-height) flex-col">
        <ScrollArea className="flex min-h-0 flex-col">
          <ul className="grid w-[200px]">
            {item.children.map((child) => (
              <li key={child.id}>
                <HeaderSubMenuItem item={child} />
              </li>
            ))}
          </ul>
        </ScrollArea>
      </NavigationMenuContent>
    </NavigationMenuItem>
  );
}

function HeaderSubMenuItem({ item }: { item: MenuItem }) {
  if (item.children.length === 0) {
    return item.url ? (
      <NavigationMenuLink
        className={item.classes}
        target={item.target}
        rel={item.rel}
        active={item.active}
        render={
          <Link href={item.url} className="flex-row items-center gap-2">
            {item.title}
          </Link>
        }
      />
    ) : (
      <span className={cn('flex-row items-center gap-2', item.classes)}>
        {item.title}
      </span>
    );
  }

  return (
    <NavigationMenuPrimitive.Root className="group/navigation-menu relative flex max-w-full flex-1 items-center justify-center">
      <NavigationMenuList className="w-full">
        <NavigationMenuItem className="w-full">
          <NavigationMenuPrimitive.Trigger
            className={cn(
              navigationMenuTriggerStyle(),
              'group h-auto w-full justify-between text-start font-normal',
              { 'bg-muted/50 hover:bg-muted focus:bg-muted': item.active },
            )}
          >
            {item.title}
            <ChevronRightIcon
              className="relative top-px ml-1 size-3 transition duration-300 group-data-open/navigation-menu-trigger:rotate-none group-data-popup-open/navigation-menu-trigger:rotate-none"
              aria-hidden="true"
            />
          </NavigationMenuPrimitive.Trigger>
          <NavigationMenuContent className="flex max-h-(--available-height) flex-col">
            <ScrollArea className="flex min-h-0 flex-col">
              <ul className="grid w-[200px]">
                {item.children.map((grandchild) => (
                  <li key={grandchild.id}>
                    <HeaderSubMenuItem item={grandchild} />
                  </li>
                ))}
              </ul>
            </ScrollArea>
          </NavigationMenuContent>
        </NavigationMenuItem>
      </NavigationMenuList>
      <NavigationMenuPositioner
        side="right"
        sideOffset={0}
        align="start"
        alignOffset={-4}
      />
    </NavigationMenuPrimitive.Root>
  );
}

function HeaderAccordionItem({ item }: { item: MenuItem }) {
  if (item.children.length === 0) {
    return item.url ? (
      <Link
        className={cn(
          navigationMenuTriggerStyle(),
          item.classes,
          'w-full flex-row items-center justify-start gap-2 no-underline hover:no-underline',
          { 'bg-muted/50 hover:bg-muted focus:bg-muted': item.active },
        )}
        target={item.target}
        rel={item.rel}
        data-active={item.active}
        href={item.url}
      >
        {item.title}
      </Link>
    ) : (
      <span
        className={cn(
          'w-full flex-row items-center justify-start gap-2 no-underline hover:no-underline',
          item.classes,
        )}
      >
        {item.title}
      </span>
    );
  }

  return (
    <Accordion hiddenUntilFound>
      <AccordionItem>
        <AccordionTrigger
          className={cn(
            navigationMenuTriggerStyle(),
            'w-full flex-row items-center justify-start gap-2 no-underline hover:no-underline',
            { 'bg-muted/50 hover:bg-muted focus:bg-muted': item.active },
          )}
        >
          {item.title}
        </AccordionTrigger>
        <AccordionContent className="[&_a]:no-underline [&_a]:hover:no-underline">
          <div className="flex flex-col gap-1 border-l pl-4">
            {item.children.map((child) => (
              <HeaderAccordionItem key={child.id} item={child} />
            ))}
          </div>
        </AccordionContent>
      </AccordionItem>
    </Accordion>
  );
}

function FooterMenuItem({ item }: { item: MenuItem }) {
  return (
    <div className="m-0 block h-auto w-auto p-0">
      <h6 className="font-medium">{item.title}</h6>
      <ul className="mt-6 space-y-4">
        {item.children.map((child) => (
          <li key={child.id}>
            <FooterMenuLink item={child} />
          </li>
        ))}
      </ul>
    </div>
  );
}

function FooterMenuLink({ item }: { item: MenuItem }) {
  return (
    <React.Fragment>
      {item.url ? (
        <Link
          href={item.url}
          target={item.target}
          rel={item.rel}
          className={cn(
            'text-muted-foreground hover:text-foreground',
            item.classes,
          )}
        >
          {item.title}
        </Link>
      ) : (
        <span className="text-foreground font-medium">{item.title}</span>
      )}

      {item.children.length > 0 && (
        <ul className="mt-4 ml-4 space-y-4 pl-2">
          {item.children.map((grandchild) => (
            <li key={grandchild.id}>
              <FooterMenuLink item={grandchild} />
            </li>
          ))}
        </ul>
      )}
    </React.Fragment>
  );
}

function Logo({ general }: { general: GeneralSettings }) {
  return (
    <div className="flex shrink-0 items-center">
      <Link href="/" className="relative">
        {general.site_logo ? (
          <AspectRatio
            ratio={16 / 9}
            className="relative w-20 overflow-hidden border-none"
          >
            <img
              className="size-full object-contain"
              sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
              src={general.site_logo}
              alt={general.site_name}
            />
          </AspectRatio>
        ) : (
          <span className="block max-w-48 truncate text-xl font-bold tracking-tight">
            {general.site_name}
          </span>
        )}
      </Link>
    </div>
  );
}

function ModeToggle() {
  const { theme, setTheme } = useTheme();

  return (
    <DropdownMenu>
      <DropdownMenuTrigger
        render={
          <Button variant="outline" size="icon">
            <SunIcon className="h-[1.2rem] w-[1.2rem] scale-100 rotate-0 transition-all dark:scale-0 dark:-rotate-90" />
            <MoonIcon className="absolute h-[1.2rem] w-[1.2rem] scale-0 rotate-90 transition-all dark:scale-100 dark:rotate-0" />
            <span className="sr-only">Toggle theme</span>
          </Button>
        }
      />
      <DropdownMenuContent align="end">
        <DropdownMenuGroup>
          <DropdownMenuLabel>Theme</DropdownMenuLabel>
          <DropdownMenuRadioGroup value={theme} onValueChange={setTheme}>
            <DropdownMenuRadioItem value="light">Light</DropdownMenuRadioItem>
            <DropdownMenuRadioItem value="dark">Dark</DropdownMenuRadioItem>
            <DropdownMenuRadioItem value="system">System</DropdownMenuRadioItem>
          </DropdownMenuRadioGroup>
        </DropdownMenuGroup>
      </DropdownMenuContent>
    </DropdownMenu>
  );
}

function SocialLinks({ socialLinks }: { socialLinks: SocialLinksSettings }) {
  const socialIcons = [
    { key: 'facebook', Icon: SiFacebook },
    { key: 'twitter', Icon: SiX },
    { key: 'instagram', Icon: SiInstagram },
    { key: 'linkedin', Icon: SiLinkedin },
    { key: 'youtube', Icon: SiYoutube },
    { key: 'github', Icon: SiGithub },
    { key: 'discord', Icon: SiDiscord },
  ] as const;

  return (
    <div className="text-muted-foreground flex items-center gap-5">
      {socialIcons.map(({ key, Icon }) => {
        const url = socialLinks[key];

        return url ? (
          <Link href={url} target="_blank" key={key}>
            <Icon className="h-5 w-5" />
          </Link>
        ) : null;
      })}
    </div>
  );
}
