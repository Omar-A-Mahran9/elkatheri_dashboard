\<div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
    <!--begin::Brand-->
    <div class="aside-logo flex-column-auto" id="kt_aside_logo" style="background: #fbf7f7 !important">
        <!--begin::Logo-->
        <a href="{{ route('dashboard.index') }}">
            <img alt="Logo" src="{{ getImagePathFromDirectory(settings()->get('logo'), 'Settings') }}" class="h-35px logo" />
        </a>
        <!--end::Logo-->
        <!--begin::Aside toggler-->
        <div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="aside-minimize">
            <!--begin::Svg Icon | path: icons/duotune/arrows/arr079.svg-->
            <span class="svg-icon svg-icon-1 rotate-180">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.5" d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z" fill="black" />
                            <path d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z" fill="black" />
                        </svg>
                    </span>
            <!--end::Svg Icon-->
        </div>
        <!--end::Aside toggler-->
    </div>
    <!--end::Brand-->
    <!--begin::Aside menu-->
    <div class="aside-menu flex-column-fluid">
        <div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="0">
            <!--begin::Menu-->
            <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500" id="#kt_aside_menu" data-kt-menu="true" data-kt-menu-expand="false">

            @canany(['view_brands' , 'view_models' , 'view_colors' , 'view_tags' , 'view_cars', 'view_services'])
                <!-- begin :: cars section -->
                <div class="menu-item">
                    <div class="menu-content pt-8 pb-0">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">{{ __("Cars") }}</span>
                    </div>
                </div>
                @endcanany


                @can('view_brands')
                <div class="menu-item">
                    <a class="menu-link {{ isTabActive('brands') }}" href="{{ route('dashboard.brands.index') }}"  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                                <i class="fa fa-list"></i>
                        </span>
                        <span class="menu-title"> {{ __("Brands") }}</span>
                    </a>
                </div>
                @endcan

                @can('view_models')
                <div class="menu-item">
                    <a class="menu-link {{ isTabActive('models','parent') }}" href="{{ route('dashboard.models.index',['type' => 'parent']) }}"  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                                <i class="fa fa-hashtag"></i>
                        </span>
                        <span class="menu-title"> {{ __("Models") }}</span>
                    </a>
                </div>
                @endcan

                {{-- @can('view_models')
                <div class="menu-item">
                    <a class="menu-link {{ isTabActive('models','sub') }}" href="{{ route('dashboard.models.index',['type' => 'sub']) }}"  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                                <i class="fa fa-hashtag"></i>
                        </span>
                        <span class="menu-title"> {{ __("Sub Models") }}</span>
                    </a>
                </div>
                @endcan --}}

                @can('view_colors')
                <div class="menu-item">
                    <a class="menu-link {{ isTabActive('colors') }}" href="{{ route('dashboard.colors.index') }}"  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                                <i class="fa fa-palette"></i>
                        </span>
                        <span class="menu-title"> {{ __("Colors") }}</span>
                    </a>
                </div>
                @endcan

                @can('view_tags')
                    <div class="menu-item">
                        <a class="menu-link {{ isTabActive('tags') }}" href="{{ route('dashboard.tags.index') }}"  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                                <i class="fa fa-tag"></i>
                        </span>
                            <span class="menu-title"> {{ __("Tags") }}</span>
                        </a>
                    </div>
                @endcan

                @can('view_cars')
                    <div class="menu-item">
                        <a class="menu-link {{ isTabActive('cars') }}" href="{{ route('dashboard.cars.index') }}"  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                                <i class="fa fa-car"></i>
                        </span>
                            <span class="menu-title"> {{ __("Cars") }}</span>
                        </a>
                    </div>
                @endcan

                @can('view_services')
                    <div class="menu-item">
                        <a class="menu-link {{ isTabActive('services') }}" href="{{ route('dashboard.services.index') }}"  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                                <i class="fa fa-wrench"></i>
                        </span>
                            <span class="menu-title"> {{ __("Services") }}</span>
                        </a>
                    </div>
                @endcan

                <!-- end   :: cars section -->


                <!-- start   :: orders section -->

                @canany(['view_orders'])
                <div class="menu-item">
                    <div class="menu-content pt-8 pb-0">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">{{ __("Maintenance Services") }}</span>
                    </div>
                </div>
                @endcanany

                @can('view_orders')
                    <div class="menu-item">
                        <a class="menu-link {{ isTabActive('appointments') }}" href="{{ route('dashboard.appointments.index') }}"  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                            <span class="menu-icon">
                                    <i class="fa fa-users"></i>
                            </span>
                            <span class="menu-title"> {{ __("Maintainance Appointments") }}</span>
                            <span class="menu-badge">
                                <span class="badge badge-danger" id="appointments_counter">{{ $pendingAppointments }}</span>
                            </span>
                        </a>
                    </div>
                @endcan
                @can('view_orders')
                    <div class="menu-item">
                        <a class="menu-link {{ isTabActive('schedules') }}" href="{{ route('dashboard.schedules.index') }}"  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                                <i class="fa fa-users"></i>
                        </span>
                            <span class="menu-title"> {{ __("Schedules") }}</span>
                        </a>
                    </div>
                @endcan


                @canany(['view_orders'])
                <div class="menu-item">
                    <div class="menu-content pt-8 pb-0">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">{{ __("Orders") }}</span>
                    </div>
                </div>
                @endcanany

                @can('view_orders')
                    <div class="menu-item">
                        <a class="menu-link {{ isTabActive('orders') }}" href="{{ route('dashboard.orders.index') }}"  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                                <i class="fa fa-users"></i>
                        </span>
                            <span class="menu-title"> {{ __("Orders") }}</span>
                        </a>
                    </div>
                @endcan

            <!-- end   :: orders section -->


                <!-- start   :: orders section -->

                @canany(['view_clients', 'view_contact_us', 'view_news_subscribers'])
                <div class="menu-item">
                    <div class="menu-content pt-8 pb-0">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">{{ __("Clients") }}</span>
                    </div>
                </div>
                @endcanany

{{--                @can('view_clients')--}}
{{--                    <div class="menu-item">--}}
{{--                        <a class="menu-link {{ isTabActive('clients') }}" href="{{ route('dashboard.clients.index') }}"  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">--}}
{{--                        <span class="menu-icon">--}}
{{--                                <i class="fa fa-users"></i>--}}
{{--                        </span>--}}
{{--                            <span class="menu-title"> {{ __("Clients") }}</span>--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                @endcan--}}


                @can('view_contact_us')
                    <div class="menu-item">
                        <a class="menu-link {{ isTabActive('contact-us') }}" href="{{ route('dashboard.contact-us.index') }}"  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                                <i class="fas fa-comments"></i>
                        </span>
                            <span class="menu-title"> {{ __("ContactUs us") }}</span>
                        </a>
                    </div>
                @endcan

                {{-- @can('view_news_subscribers')
                    <div class="menu-item">
                        <a class="menu-link {{ isTabActive('news-subscribers') }}" href="{{ route('dashboard.news-subscribers.index') }}"  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                                <i class="fas fa-newspaper"></i>
                        </span>
                            <span class="menu-title"> {{ __("News Subscribers") }}</span>
                        </a>
                    </div>
                @endcan --}}

                <!-- end   :: clients section -->


                @canany(['view_careers', 'view_news', 'view_faq', 'view_offers', 'view_funding_organizations'])
                <div class="menu-item">
                    <div class="menu-content pt-8 pb-0">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">{{ __("Website") }}</span>
                    </div>
                </div>
                @endcanany

                @can('view_careers')
                    <div class="menu-item">
                        <a class="menu-link {{ isTabActive('careers') }}" href="{{ route('dashboard.careers.index') }}"  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                                <i class="fa fa-briefcase"></i>
                        </span>
                            <span class="menu-title"> {{ __("Careers") }}</span>
                        </a>
                    </div>
                @endcan


                @can('view_news')
                    <div class="menu-item">
                        <a class="menu-link {{ isTabActive('news') }}" href="{{ route('dashboard.news.index') }}"  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                                <i class="fa fa-newspaper"></i>
                        </span>
                            <span class="menu-title"> {{ __("News") }}</span>
                        </a>
                    </div>
                @endcan


                @can('view_faq')
                    <div class="menu-item">
                        <a class="menu-link {{ isTabActive('faqs') }}" href="{{ route('dashboard.faqs.index') }}"  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                                <i class="fa fa-question-circle"></i>
                        </span>
                            <span class="menu-title"> {{ __("FAQ") }}</span>
                        </a>
                    </div>
                @endcan

                @can('view_offers')
                    <div class="menu-item">
                        <a class="menu-link {{ isTabActive('offers') }}" href="{{ route('dashboard.offers.index') }}"  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                                <i class="fa fa-gifts"></i>
                        </span>
                            <span class="menu-title"> {{ __("Offers") }}</span>
                        </a>
                    </div>
                @endcan

                @can('view_funding_organizations')
                    <div class="menu-item">
                        <a class="menu-link {{ isTabActive('funding-organizations') }}" href="{{ route('dashboard.funding-organizations.index') }}"  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                                <i class="fas fa-coins"></i>
                        </span>
                            <span class="menu-title"> {{ __("Funding Organizations") }}</span>
                        </a>
                    </div>
                @endcan

                @canany(['view_cities' , 'view_branches' , 'view_banks' , 'view_roles' , 'view_employees' , 'view_settings'])
                    <div class="menu-item">
                        <div class="menu-content pt-8 pb-0">
                            <span class="menu-section text-muted text-uppercase fs-8 ls-1">{{ __("Settings") }}</span>
                        </div>
                    </div>
                @endcanany


                @can('view_cities')
                    <div class="menu-item">
                        <a class="menu-link {{ isTabActive('cities') }}" href="{{ route('dashboard.cities.index') }}"  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                                <i class="fa fa-city"></i>
                        </span>
                            <span class="menu-title"> {{ __("Cities") }}</span>
                        </a>
                    </div>
                @endcan

                @can('view_branches')
                    <div class="menu-item">
                        <a class="menu-link {{ isTabActive('branches') }}" href="{{ route('dashboard.branches.index') }}"  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                                <i class="fa fa-building"></i>
                        </span>
                            <span class="menu-title"> {{ __("Branches") }}</span>
                        </a>
                    </div>
                @endcan

                @can('view_banks')
                    <div class="menu-item">
                        <a class="menu-link {{ isTabActive('banks') }}" href="{{ route('dashboard.banks.index') }}"  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                                <i class="bi bi-bank"></i>
                        </span>
                            <span class="menu-title"> {{ __("Banks") }}</span>
                        </a>
                    </div>
                @endcan

                @can('view_roles')
                    <div class="menu-item">
                        <a class="menu-link {{ isTabActive('roles') }}" href="{{ route('dashboard.roles.index') }}"  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                                <i class="fa fa-lock"></i>
                        </span>
                            <span class="menu-title"> {{ __("Roles") }}</span>
                        </a>
                    </div>
                @endcan

                @can('view_employees')
                    <div class="menu-item">
                        <a class="menu-link {{ isTabActive('employees') }}" href="{{ route('dashboard.employees.index') }}"  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                                <i class="fa fa-user-shield"></i>
                        </span>
                            <span class="menu-title"> {{ __("Employees") }}</span>
                        </a>
                    </div>
                @endcan

                @can('view_settings')
                    <div class="menu-item">
                        <a class="menu-link {{ isTabActive('settings') }}" href="{{ route('dashboard.settings.index') }}"  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                                <i class="bi bi-gear-fill"></i>
                        </span>
                            <span class="menu-title"> {{ __("Settings") }}</span>
                        </a>
                    </div>
                @endcan

                @can('view_recycle_bin')
                    <div class="menu-item">
                        <a class="menu-link {{ isTabActive('trash') }}" href="{{ route('dashboard.trash') }}"  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                                <i class="fas fa-trash"></i>
                        </span>
                            <span class="menu-title"> {{ __("Recycle Bin") }}</span>
                        </a>
                    </div>
                @endcan


            </div>
            <!--end::Menu-->
        </div>
    </div>
    <!--end::Aside menu-->
</div>
