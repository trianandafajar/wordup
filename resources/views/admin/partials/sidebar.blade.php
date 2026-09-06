<aside x-data="{ sidebarHover: false }"
    :class="[
        sidebarToggle ? 'sidebar-hover-collapsed translate-x-0 lg:w-[90px]' : '-translate-x-full lg:w-[290px]',
        sidebarHover ? 'sidebar-hover' : '',
    ]"
    @mouseenter="if (sidebarToggle) sidebarHover = true"
    @mouseleave="sidebarHover = false"
    class="sidebar fixed left-0 top-0 z-999999 flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 duration-300 ease-linear dark:border-gray-800 dark:bg-gray-900 lg:translate-x-0 lg:px-2"
    @click.outside="if (window.innerWidth < 1024) sidebarToggle = false">
    <!-- SIDEBAR HEADER -->
    <div :class="sidebarToggle ? 'justify-center' : 'justify-between'"
        class="sidebar-header flex items-center gap-2 pb-7 pt-8">
        <a href="{{ filament()->getUrl() }}" class="flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto" />
            <span class="menu-item-text text-xl font-bold text-brand-600 dark:text-brand-400" :class="sidebarToggle ? 'hidden' : 'inline lg:inline'">WordUp</span>
        </a>
    </div>

    <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear">
        <nav>
            <div>
                <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
                    <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">MENU</span>
                </h3>

                <ul class="mb-6 flex flex-col gap-1.5">
                    <li>
                        <a href="{{ filament()->getUrl() }}"
                            class="menu-item group {{ request()->routeIs('filament.admin.pages.dashboard') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <svg :class="({{ request()->routeIs('filament.admin.pages.dashboard') ? 'true' : 'false' }}) ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                class="shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V8.99998C14.25 9.41419 14.5858 9.74998 15 9.74998H18.5C18.9142 9.74998 19.25 9.41419 19.25 8.99998V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15ZM14.25 15C14.25 14.5858 14.5858 14.25 15 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z"
                                    fill="currentColor" />
                            </svg>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Dashboard</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
                    <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">Content</span>
                </h3>
                <ul class="mb-6 flex flex-col gap-1.5">
                    <li>
                        <a href="{{ \App\Filament\Resources\CourseResource::getUrl('index') }}"
                            class="menu-item group {{ request()->routeIs('filament.admin.resources.courses.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <svg :class="({{ request()->routeIs('filament.admin.resources.courses.*') ? 'true' : 'false' }}) ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                class="shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 5.25C9.586 5.25 7.586 5.25 5.25 5.25C4.007 5.25 3.25 6.007 3.25 7.5V16.5C3.25 17.743 4.007 18.5 5.25 18.5C7.586 18.5 9.586 18.5 12 18.5C14.414 18.5 16.414 18.5 18.75 18.5C19.993 18.5 20.75 17.743 20.75 16.5V7.5C20.75 6.007 19.993 5.25 18.75 5.25C16.414 5.25 14.414 5.25 12 5.25ZM12 17C9.243 17 7.03 16.993 4.75 16.972V7.528C7.03 7.507 9.243 7.5 12 7.5C14.757 7.5 16.97 7.507 19.25 7.528V16.972C16.97 16.993 14.757 17 12 17Z"
                                    fill="currentColor" />
                            </svg>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Courses</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ \App\Filament\Resources\UnitResource::getUrl('index') }}"
                            class="menu-item group {{ request()->routeIs('filament.admin.resources.units.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <svg :class="({{ request()->routeIs('filament.admin.resources.units.*') ? 'true' : 'false' }}) ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                class="shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M3.5 8.187V17.25C3.5 17.6642 3.83579 18 4.25 18H19.75C20.1642 18 20.5 17.6642 20.5 17.25V8.18747L13.2873 13.2171C12.5141 13.7563 11.4866 13.7563 10.7134 13.2171L3.5 8.187ZM20.5 6.2286C20.5 6.23039 20.5 6.23218 20.5 6.23398V6.24336C20.4976 6.31753 20.4604 6.38643 20.3992 6.42905L12.4293 11.9867C12.1716 12.1664 11.8291 12.1664 11.5713 11.9867L3.60116 6.42885C3.538 6.38481 3.50035 6.31268 3.50032 6.23568C3.50028 6.10553 3.60577 6 3.73592 6H20.2644C20.3922 6 20.4963 6.10171 20.5 6.2286ZM22 6.25648V17.25C22 18.4926 20.9926 19.5 19.75 19.5H4.25C3.00736 19.5 2 18.4926 2 17.25V6.23398C2 6.22371 2.00021 6.2135 2.00061 6.20333C2.01781 5.25971 2.78812 4.5 3.73592 4.5H20.2644C21.2229 4.5 22 5.27697 22.0001 6.23549C22.0001 6.24249 22.0001 6.24949 22 6.25648Z"
                                    fill="currentColor" />
                            </svg>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Units</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ \App\Filament\Resources\LessonResource::getUrl('index') }}"
                            class="menu-item group {{ request()->routeIs('filament.admin.resources.lessons.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <svg :class="({{ request()->routeIs('filament.admin.resources.lessons.*') ? 'true' : 'false' }}) ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                class="shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M10.5 3.75C10.5 3.33579 10.8358 3 11.25 3H19.5C19.9142 3 20.25 3.33579 20.25 3.75C20.25 4.16421 19.9142 4.5 19.5 4.5H11.25C10.8358 4.5 10.5 4.16421 10.5 3.75ZM10.5 7.75C10.5 7.33579 10.8358 7 11.25 7H19.5C19.9142 7 20.25 7.33579 20.25 7.75C20.25 8.16421 19.9142 8.5 19.5 8.5H11.25C10.8358 8.5 10.5 8.16421 10.5 7.75ZM10.5 11.75C10.5 11.3358 10.8358 11 11.25 11H19.5C19.9142 11 20.25 11.3358 20.25 11.75C20.25 12.1642 19.9142 12.5 19.5 12.5H11.25C10.8358 12.5 10.5 12.1642 10.5 11.75ZM7 3.75C7 4.16421 6.66421 4.5 6.25 4.5H5.5C5.08579 4.5 4.75 4.83579 4.75 5.25V19.25C4.75 19.6642 5.08579 20 5.5 20H18.5C18.9142 20 19.25 19.6642 19.25 19.25V18.5C19.25 18.0858 19.5858 17.75 20 17.75C20.4142 17.75 20.75 18.0858 20.75 18.5V19.25C20.75 20.4926 19.7426 21.5 18.5 21.5H5.5C4.25736 21.5 3.25 20.4926 3.25 19.25V5.25C3.25 4.00736 4.25736 3 5.5 3H6.25C6.66421 3 7 3.33579 7 3.75ZM12.25 19.75C12.25 20.1642 11.9142 20.5 11.5 20.5C11.0858 20.5 10.75 20.1642 10.75 19.75V18.25C10.75 17.8358 11.0858 17.5 11.5 17.5C11.9142 17.5 12.25 17.8358 12.25 18.25V19.75Z"
                                    fill="currentColor" />
                            </svg>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Lessons</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ \App\Filament\Resources\QuestionResource::getUrl('index') }}"
                            class="menu-item group {{ request()->routeIs('filament.admin.resources.questions.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <svg :class="({{ request()->routeIs('filament.admin.resources.questions.*') ? 'true' : 'false' }}) ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                class="shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2ZM12 5.75C12.4142 5.75 12.75 6.08579 12.75 6.5V12C12.75 12.4142 12.4142 12.75 12 12.75C11.5858 12.75 11.25 12.4142 11.25 12V6.5C11.25 6.08579 11.5858 5.75 12 5.75ZM12 14.75C12.4142 14.75 12.75 15.0858 12.75 15.5C12.75 15.9142 12.4142 16.25 12 16.25C11.5858 16.25 11.25 15.9142 11.25 15.5C11.25 15.0858 11.5858 14.75 12 14.75Z"
                                    fill="currentColor" />
                            </svg>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Questions</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
                    <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">User</span>
                </h3>
                <ul class="mb-6 flex flex-col gap-1.5">
                    <li>
                        <a href="{{ \App\Filament\Resources\UserResource::getUrl('index') }}"
                            class="menu-item group {{ request()->routeIs('filament.admin.resources.users.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <svg :class="({{ request()->routeIs('filament.admin.resources.users.*') ? 'true' : 'false' }}) ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                class="shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2ZM8.5 10.5C8.5 8.567 10.067 7 12 7C13.933 7 15.5 8.567 15.5 10.5C15.5 12.433 13.933 14 12 14C10.067 14 8.5 12.433 8.5 10.5ZM12 20.5C9.189 20.5 6.66 19.13 5.126 17C5.863 14.89 7.89 13.5 10.25 13.5H13.75C16.11 13.5 18.137 14.89 18.874 17C17.34 19.13 14.811 20.5 12 20.5Z"
                                    fill="currentColor" />
                            </svg>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Users</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</aside>