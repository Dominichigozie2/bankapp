<div id="main-sidebar"
    class="main-sidebar group-data-[layout=boxed]:top-[calc(theme('spacing.topbar')_+_theme('spacing.sidebar-boxed'))] hidden lg:block"
    :class="{ 'navbar': true, 'group-data-[layout=boxed]:!top-topbar': scrolled }">
    <div class="sidebar-wrapper">
        <div class="border-b border-sidebar-divider dark:border-dark-800">
            <div class="navbar-brand">
                <a href="index.html" class="inline-flex items-center justify-center w-full">
                    <div class="group-data-[sidebar=small]:hidden">
                        <img src="assets/images/main-logo.png" aria-label="logo" alt=""
                            class="h-6 mx-auto group-data-[sidebar-colors=light]:dark:hidden group-data-[sidebar-colors=dark]:hidden group-data-[sidebar-colors=brand]:hidden group-data-[sidebar-colors=purple]:hidden group-data-[sidebar-colors=sky]:hidden" />
                        <img src="assets/images/logo-white.png" aria-label="logo" alt=""
                            class="h-6 mx-auto group-data-[sidebar-colors=light]:hidden group-data-[sidebar-colors=light]:dark:inline-block" />
                    </div>
                    <div class="hidden group-data-[sidebar=small]:inline-block">
                        <img src="assets/images/logo-sm-dark.png" aria-label="logo" alt="" class="h-6 mx-auto" />
                    </div>
                </a>
            </div>
        </div>
        <div class="fixed top-0 bottom-0 left-0 w-20 bg-white bg-light hidden group-data-[layout=doulcolumn]:block"></div>
        <div class="navbar-menu" data-simplebar id="navbar-menu-list" x-data="sidebarMenu()" x-init="init()">
            <ul class="group-data-[layout=horizontal]:md:flex group-data-[layout=horizontal]:*:shrink-0" id="sidebar">
                <template x-for="(item, index) in filteredMenu" :key="`${index}-${item.title}`" :attr="$data.layout">
                    <li x-data="dropdownBehavior()" x-init="initializeDropdownActive()" x-transition.scale.origin.top
                        @open-parent-dropdown.window="open = false; calculatePosition();"
                        :class="item.separator ? 'menu-title' : 'relative'">
                        <template x-if="!item.children.length && item.separator"><span class="group-data-[sidebar=small]:hidden"
                                x-text="$data.languageData[item.lang] ?? item.title"></span>
                            <i data-lucide="ellipsis"
                                class="hidden group-data-[sidebar=small]:block mx-auto size-4"></i></template><template
                            x-if="!item.separator && item.children.length"><button x-ref="button"
                                x-on:click="!item.separator && toggle()" :aria-expanded="open.toString()" type="button"
                                class="nav-link">
                                <span class="w-7 group-data-[sidebar=small]:mx-auto shrink-0"><i
                                        :class="item.icon + ' text-xl '"></i></span><template x-if="item.children.length"><span
                                        class="content" x-text="$data.languageData[item.lang] ?? item.title"></span></template><template
                                    x-if="item.children.length > 0"><svg :class="{ 'transform rotate-180': open }" class="arrow"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg></template></button></template><template x-if="!item.children.length && !item.separator"><a
                                :href="item.link" x-ref="navLink" class="nav-link"><span
                                    class="w-7 group-data-[sidebar=small]:mx-auto shrink-0"><i :class="item.icon + ' text-xl '"></i>
                                </span><span class="group-data-[sidebar=small]:hidden"
                                    x-text="$data.languageData[item.lang] ?? item.title"></span></a></template><template
                            x-if="item.children.length && !item.megaMenu">
                            <div data-parent-yes x-ref="dropdown" x-show="open" x-transition.origin.top.left class="dropdown-menu"
                                :dropdown-position="($data.layout === 'horizontal') ? 'left' : 'right-top'"
                                x-on:click.outside="open = getOpen($event)">
                                <ul class="dropdown-wrapper">
                                    <template x-for="(child, index2) in item.children" :key="`${index2}--${child.title}`">
                                        <li>
                                            <template x-if="child.children.length === 0"><a :href="child.link"
                                                    x-text="$data.languageData[child.lang] ?? child.title"></a></template><template
                                                x-if="child.children.length > 0">
                                                <div x-data="dropdownBehavior()" x-transition.scale.origin.top
                                                    x-init="initializeDropdownActive()"
                                                    @open-parent-dropdown.window="open = false; calculatePosition();" class="relative">
                                                    <template x-if="child.children.length">
                                                        <div>
                                                            <button x-ref="button" x-on:click="toggleMenu()" :aria-expanded="open.toString()"
                                                                type="button" class="nav-link">
                                                                <span x-text="$data.languageData[child.lang] ?? child.title"></span><template
                                                                    x-if="child.children.length > 0"><svg :class="{ 'transform rotate-180': open }"
                                                                        class="arrow" viewBox="0 0 20 20" fill="currentColor">
                                                                        <path fill-rule="evenodd"
                                                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                            clip-rule="evenodd" />
                                                                    </svg></template>
                                                            </button>
                                                            <div x-ref="dropdown" x-show="open" x-transition.scale.origin.top class="dropdown-menu"
                                                                dropdown-position="right-top" x-on:click.outside="open = getOpen($event)">
                                                                <ul class="dropdown-wrapper">
                                                                    <template x-for="(child2, index3) in child.children"
                                                                        :key="`${index3}-1-${child2.title}`">
                                                                        <li class="relative">
                                                                            <a :href="child2.link"
                                                                                x-text="$data.languageData[child2.lang] ?? child2.title"></a>
                                                                        </li>
                                                                    </template>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </template><template x-if="item.children.length && item.megaMenu">
                            <div data-parent-yes x-ref="dropdown" x-show="open" x-transition.origin.top.left
                                class="dropdown-menu mega-menu"
                                :dropdown-position="($data.layout === 'horizontal') ? 'left' : 'right-top'"
                                x-on:click.outside="open = getOpen($event)">
                                <div class="dropdown-wrapper">
                                    <div class="group-data-[layout=horizontal]:md:grid group-data-[layout=horizontal]:md:grid-cols-2">
                                        <template
                                            x-for="(chunk, index2) in chunkArray(item.children, Math.ceil(item.children.length / 2))"
                                            :key="`${index2}--mega`">
                                            <ul>
                                                <template x-for="(item5, index4) in chunk" :key="`${item5.name}--chunk${index4}`">
                                                    <li>
                                                        <a :href="item5.link" x-text="$data.languageData[item5.lang] ?? item5.name"></a>
                                                    </li>
                                                </template>
                                            </ul>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</div>