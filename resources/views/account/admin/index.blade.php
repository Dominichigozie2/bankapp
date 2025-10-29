        @extends('account.admin.layout.apps')
        @section('content')

 <div class="relative min-h-screen group-data-[layout=boxed]:bg-white group-data-[layout=boxed]:rounded-md">
    <div class="page-wrapper">
      <div class="flex-col items-start gap-1 page-heading sm:flex-row sm:items-center">
        <h6 class="grow group-data-[nav-type=pattern]:text-white">Dashboard</h6>
        <ul class="breadcrumb *:before:content-['\EA6E']">
          <li class="breadcrumb-item"><a href="#!">Dashboards</a></li>
          <li class="breadcrumb-item active">Bank</li>
        </ul>
      </div>
      <div class="grid grid-cols-12 gap-x-space">
        <div class="relative order-1 col-span-12 md:col-span-4 2xl:col-span-2 card">
          <div class="card-body">
            <div class="flex items-center gap-2 mb-7">
              <div class="flex items-center justify-center rounded-md bg-primary-500 size-7 text-primary-50">
                <i data-lucide="graduation-cap" class="size-5"></i>
              </div>
              <p class="text-gray-500 dark:text-dark-500">Total Students</p>
            </div>
            <h5
              class="relative inline-block mb-2 before:absolute before:border-b-2 before:border-primary-500 before:inset-x-0 before:-bottom-1">
              <span x-data="animatedCounter(1478, 500, 0)" x-init="updateCounter" x-text="Math.round(current)"></span>
            </h5>
          </div>
        </div>
        <div class="relative order-2 col-span-12 md:col-span-4 2xl:col-span-2 card">
          <div class="card-body">
            <div class="flex items-center gap-2 mb-7">
              <div class="flex items-center justify-center bg-orange-500 rounded-md size-7 text-orange-50">
                <i data-lucide="user-round" class="size-5"></i>
              </div>
              <p class="text-gray-500 dark:text-dark-500">Total Teachers</p>
            </div>
            <h5
              class="relative inline-block mb-2 before:absolute before:border-b-2 before:border-orange-500 before:inset-x-0 before:-bottom-1">
              <span x-data="animatedCounter(120, 500, 0)" x-init="updateCounter" x-text="Math.round(current)"></span>
            </h5>
          </div>
        </div>
        <div class="relative order-3 col-span-12 md:col-span-4 2xl:col-span-2 card">
          <div class="card-body">
            <div class="flex items-center gap-2 mb-7">
              <div class="flex items-center justify-center rounded-md bg-sky-500 size-7 text-sky-50">
                <i data-lucide="book-open" class="size-5"></i>
              </div>
              <p class="text-gray-500 dark:text-dark-500">Total Courses</p>
            </div>
            <h5
              class="relative inline-block mb-2 before:absolute before:border-b-2 before:border-sky-500 before:inset-x-0 before:-bottom-1">
              <span x-data="animatedCounter(120, 500, 0)" x-init="updateCounter" x-text="Math.round(current)"></span>
            </h5>
          </div>
        </div>
        <div class="relative order-7 col-span-12 2xl:order-4 2xl:col-span-3 2xl:row-span-2 card">
          <div class="flex items-center gap-3 card-header">
            <h6 class="card-title grow">Course Activities</h6>
            <div x-data="dropdownBehavior()" x-on:keydown.escape.prevent.stop="close()" x-init="calculatePosition()"
              class="dropdown shrink-0">
              <button x-ref="button" x-on:click="toggle()" title="dropdown-button" :aria-expanded="open.toString()"
                type="button" class="flex items-center text-gray-500 dark:text-dark-500">
                <i data-lucide="ellipsis" class="size-5"></i>
              </button>
              <div x-ref="dropdown" x-show="open" x-transition.origin.top.right x-on:click.outside="close()"
                class="!fixed p-2 dropdown-menu hidden" dropdown-position="right">
                <ul>
                  <li>
                    <a href="#!" class="dropdown-item"><span>Weekly</span></a>
                  </li>
                  <li>
                    <a href="#!" class="dropdown-item"><span>Monthly</span></a>
                  </li>
                  <li>
                    <a href="#!" class="dropdown-item"><span>Yearly</span></a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div x-data="gradientDonutApp" dir="ltr">
              <div class="!min-h-full" data-chart-colors="[bg-gray-200, bg-primary-500]"
                data-chart-dark-colors="[bg-dark-850, bg-primary-500]" x-ref="gradientDonutChart"></div>
            </div>
          </div>
        </div>
        <div class="order-8 col-span-12 2xl:order-5 2xl:col-span-3 2xl:row-span-4 card">
          <div class="card-body">
            <div class="mb-5">
              <div x-data="calendar()">
                <h6 class="mb-3" x-text="monthName + ', ' + year"></h6>
                <div data-simplebar id="horizontalScroll">
                  <div class="flex items-center gap-2">
                    <template x-for="day in daysInMonth" :key="day"><a href="#!" :class="{
                                               'active': day === today,
                                               'flex items-center justify-center font-medium text-lg bg-gray-100 dark:bg-dark-850 rounded-md size-12 shrink-0 [&.active]:bg-primary-500 [&.active]:text-primary-50 [&.active]:border-primary-500': true
                                           }" x-text="day"></a></template>
                  </div>
                </div>
              </div>
            </div>
            <div class="flex items-center gap-3 mb-4">
              <h6 class="grow">Holiday Lists</h6>
              <a href="#!" class="shrink-0 text-13 link link-primary text-primary-500">View More
                <i data-lucide="move-right" class="inline-block size-4"></i></a>
            </div>
            <div class="swiper mySwiper" dir="ltr">
              <div class="swiper-wrapper">
                <div class="swiper-slide">
                  <div class="relative border-dashed shadow-none card">
                    <div class="flex items-center gap-3 card-body">
                      <div class="grow">
                        <h6 class="mb-1">
                          <i data-lucide="circle-dot"
                            class="inline-block text-green-500 ltr:mr-1 rtl:ml-1 size-4 fill-green-500/15"></i>
                          World Braille Day
                        </h6>
                        <p class="text-gray-500 dark:text-dark-500 text-13">
                          04 Jan, 2024
                        </p>
                      </div>
                      <img
                        src="data:image/png;base64,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"
                        alt="" class="shrink-0 size-10" />
                    </div>
                  </div>
                </div>
                <div class="swiper-slide">
                  <div class="relative border-dashed shadow-none card">
                    <div class="flex items-center gap-3 card-body">
                      <div class="grow">
                        <h6 class="mb-1">
                          <i data-lucide="circle-dot"
                            class="inline-block text-green-500 ltr:mr-1 rtl:ml-1 size-4 fill-green-500/15"></i>
                          Earth Hour
                        </h6>
                        <p class="text-gray-500 dark:text-dark-500 text-13">
                          23 March 2024
                        </p>
                      </div>
                      <img
                        src="data:image/png;base64,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"
                        alt="" class="shrink-0 size-10" />
                    </div>
                  </div>
                </div>
                <div class="swiper-slide">
                  <div class="relative border-dashed shadow-none card">
                    <div class="flex items-center gap-3 card-body">
                      <div class="grow">
                        <h6 class="mb-1">
                          <i data-lucide="circle-dot"
                            class="inline-block text-green-500 ltr:mr-1 rtl:ml-1 size-4 fill-green-500/15"></i>
                          Software Freedom Day
                        </h6>
                        <p class="text-gray-500 dark:text-dark-500 text-13">
                          15 Sep 2024
                        </p>
                      </div>
                      <img
                        src="data:image/png;base64,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"
                        alt="" class="shrink-0 size-10" />
                    </div>
                  </div>
                </div>
                <div class="swiper-slide">
                  <div class="relative border-dashed shadow-none card">
                    <div class="flex items-center gap-3 card-body">
                      <div class="grow">
                        <h6 class="mb-1">
                          <i data-lucide="circle-dot"
                            class="inline-block text-green-500 ltr:mr-1 rtl:ml-1 size-4 fill-green-500/15"></i>
                          Inventors Day
                        </h6>
                        <p class="text-gray-500 dark:text-dark-500 text-13">
                          29 Sep 2024
                        </p>
                      </div>
                      <img
                        src="data:image/png;base64,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"
                        alt="" class="shrink-0 size-10" />
                    </div>
                  </div>
                </div>
                <div class="swiper-slide">
                  <div class="relative border-dashed shadow-none card">
                    <div class="flex items-center gap-3 card-body">
                      <div class="grow">
                        <h6 class="mb-1">
                          <i data-lucide="circle-dot"
                            class="inline-block text-green-500 ltr:mr-1 rtl:ml-1 size-4 fill-green-500/15"></i>
                          World Teacher's Day
                        </h6>
                        <p class="text-gray-500 dark:text-dark-500 text-13">
                          05 Oct 2024
                        </p>
                      </div>
                      <img
                        src="data:image/png;base64,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"
                        alt="" class="shrink-0 size-10" />
                    </div>
                  </div>
                </div>
                <div class="swiper-slide">
                  <div class="relative border-dashed shadow-none card">
                    <div class="flex items-center gap-3 card-body">
                      <div class="grow">
                        <h6 class="mb-1">
                          <i data-lucide="circle-dot"
                            class="inline-block text-green-500 ltr:mr-1 rtl:ml-1 size-4 fill-green-500/15"></i>
                          Human Rights Day
                        </h6>
                        <p class="text-gray-500 dark:text-dark-500 text-13">
                          10 Dec 2024
                        </p>
                      </div>
                      <img
                        src="data:image/png;base64,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"
                        alt="" class="shrink-0 size-10" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="relative">
              <h6 class="mb-4">Notice Board</h6>
              <div class="space-y-5">
                <div class="flex items-center gap-3">
                  <img src="assets/images/img-05.jpg" alt="" class="object-cover w-16 h-20 rounded-md" />
                  <div>
                    <h6 class="mb-2 line-clamp-2">
                      <a href="#!"
                        class="text-current dark:text-current dark:hover:text-primary-500 link link-primary">Beginning
                        or end of the school year</a>
                    </h6>
                    <p class="text-gray-500 dark:text-dark-500">
                      By Erwin Legros
                    </p>
                  </div>
                </div>
                <div class="flex items-center gap-3">
                  <img src="assets/images/img-03.jpg" alt="" class="object-cover w-16 h-20 rounded-md" />
                  <div>
                    <h6 class="mb-2 line-clamp-2">
                      <a href="#!"
                        class="text-current dark:text-current dark:hover:text-primary-500 link link-primary">Motivational
                        or inspirational quotes</a>
                    </h6>
                    <p class="text-gray-500 dark:text-dark-500">
                      By Ardith Bode
                    </p>
                  </div>
                </div>
                <div class="flex items-center gap-3">
                  <img src="assets/images/img-04.jpg" alt="" class="object-cover w-16 h-20 rounded-md" />
                  <div>
                    <h6 class="mb-2 line-clamp-2">
                      <a href="#!"
                        class="text-current dark:text-current dark:hover:text-primary-500 link link-primary">Examination
                        & Parent - Teacher Meeting</a>
                    </h6>
                    <p class="text-gray-500 dark:text-dark-500">
                      By Gerhard Boyle
                    </p>
                  </div>
                </div>
                <div class="flex items-center gap-3">
                  <img src="assets/images/img-02.jpg" alt="" class="object-cover w-16 h-20 rounded-md" />
                  <div>
                    <h6 class="mb-2 line-clamp-2">
                      <a href="#!"
                        class="text-current dark:text-current dark:hover:text-primary-500 link link-primary">Elementary
                        school student activities</a>
                    </h6>
                    <p class="text-gray-500 dark:text-dark-500">
                      By Maci Crist
                    </p>
                  </div>
                </div>
                <div class="flex items-center gap-3">
                  <img src="assets/images/img-01.jpg" alt="" class="object-cover w-16 h-20 rounded-md" />
                  <div>
                    <h6 class="mb-2 line-clamp-2">
                      <a href="#!"
                        class="text-current dark:text-current dark:hover:text-primary-500 link link-primary">Random acts
                        of kindness board compare and contrast</a>
                    </h6>
                    <p class="text-gray-500 dark:text-dark-500">
                      By Ruthie Blanda
                    </p>
                  </div>
                </div>
                <div class="flex items-center gap-3">
                  <img src="assets/images/img-06.jpg" alt="" class="object-cover w-16 h-20 rounded-md" />
                  <div>
                    <h6 class="mb-2 line-clamp-2">
                      <a href="#!"
                        class="text-current dark:text-current dark:hover:text-primary-500 link link-primary">A Book a
                        Day Keeps the Monsters Away</a>
                    </h6>
                    <p class="text-gray-500 dark:text-dark-500">
                      By Magnus Miller
                    </p>
                  </div>
                </div>
                <div class="flex items-center gap-3">
                  <img src="assets/images/img-07.jpg" alt="" class="object-cover w-16 h-20 rounded-md" />
                  <div>
                    <h6 class="mb-2 line-clamp-2">
                      <a href="#!"
                        class="text-current dark:text-current dark:hover:text-primary-500 link link-primary">Student
                        Work Coming Bulletin Board</a>
                    </h6>
                    <p class="text-gray-500 dark:text-dark-500">
                      By Jeremie Thiel
                    </p>
                  </div>
                </div>
                <div class="flex items-center gap-3">
                  <img src="assets/images/img-08.jpg" alt="" class="object-cover w-16 h-20 rounded-md" />
                  <div>
                    <h6 class="mb-2 line-clamp-2">
                      <a href="#!"
                        class="text-current dark:text-current dark:hover:text-primary-500 link link-primary">Add a
                        Little Sunshine to Someone’s Day</a>
                    </h6>
                    <p class="text-gray-500 dark:text-dark-500">
                      By Treva Trantow
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="relative order-4 col-span-12 2xl:order-6 md:col-span-4 2xl:col-span-2 card">
          <div class="card-body">
            <div class="flex items-center gap-2 mb-7">
              <div class="flex items-center justify-center bg-yellow-500 rounded-md size-7 text-yellow-50">
                <i data-lucide="computer" class="size-5"></i>
              </div>
              <p class="text-gray-500 dark:text-dark-500">Class Rooms</p>
            </div>
            <h5
              class="relative inline-block mb-2 before:absolute before:border-b-2 before:border-yellow-500 before:inset-x-0 before:-bottom-1">
              <span x-data="animatedCounter(65, 500, 0)" x-init="updateCounter" x-text="Math.round(current)"></span>
            </h5>
          </div>
        </div>
        <div class="relative order-5 col-span-12 2xl:order-7 md:col-span-4 2xl:col-span-2 card">
          <div class="card-body">
            <div class="flex items-center gap-2 mb-7">
              <div class="flex items-center justify-center bg-pink-500 rounded-md size-7 text-pink-50">
                <i data-lucide="hand-coins" class="size-5"></i>
              </div>
              <p class="text-gray-500 dark:text-dark-500">Total Earnings</p>
            </div>
            <h5
              class="relative inline-block mb-2 before:absolute before:border-b-2 before:border-pink-500 before:inset-x-0 before:-bottom-1">
              $<span x-data="animatedCounter(487, 500, 0)" x-init="updateCounter" x-text="Math.round(current)"></span>M
            </h5>
          </div>
        </div>
        <div class="relative order-6 col-span-12 2xl:order-8 md:col-span-4 2xl:col-span-2 card">
          <div class="card-body">
            <div class="flex items-center gap-2 mb-7">
              <div class="flex items-center justify-center bg-purple-500 rounded-md size-7 text-purple-50">
                <i data-lucide="medal" class="size-5"></i>
              </div>
              <p class="text-gray-500 dark:text-dark-500">Awards</p>
            </div>
            <h5
              class="relative inline-block mb-2 before:absolute before:border-b-2 before:border-purple-500 before:inset-x-0 before:-bottom-1">
              <span x-data="animatedCounter(30, 500, 0)" x-init="updateCounter" x-text="Math.round(current)"></span>+
            </h5>
          </div>
        </div>
        <div class="order-9 col-span-12 md:col-span-6 2xl:col-span-6 card">
          <div class="flex items-center gap-3 card-header">
            <h6 class="card-title grow">Total Students</h6>
            <div x-data="dropdownBehavior()" x-on:keydown.escape.prevent.stop="close()" x-init="calculatePosition()"
              class="dropdown shrink-0">
              <button x-ref="button" x-on:click="toggle()" title="dropdown-button" :aria-expanded="open.toString()"
                type="button" class="flex items-center text-gray-500 dark:text-dark-500">
                <i data-lucide="ellipsis" class="size-5"></i>
              </button>
              <div x-ref="dropdown" x-show="open" x-transition.origin.top.right x-on:click.outside="close()"
                class="!fixed p-2 dropdown-menu hidden" dropdown-position="right">
                <ul>
                  <li>
                    <a href="#!" class="dropdown-item"><span>Weekly</span></a>
                  </li>
                  <li>
                    <a href="#!" class="dropdown-item"><span>Monthly</span></a>
                  </li>
                  <li>
                    <a href="#!" class="dropdown-item"><span>Yearly</span></a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div x-data="dumbbellColumnApp" dir="ltr">
              <div class="!min-h-full" data-chart-colors="[bg-primary-500, bg-red-500]" x-ref="dumbbellColumnChart">
              </div>
            </div>
          </div>
        </div>
        <div class="order-10 col-span-12 md:col-span-6 2xl:col-span-3 card">
          <div class="card-header">
            <h6 class="card-title">Upcoming Test</h6>
          </div>
          <div class="space-y-3 card-body">
            <div class="flex items-center gap-3">
              <div class="flex items-center justify-center rounded-md bg-orange-500/15 size-12">
                <img
                  src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAABuwAAAbsBOuzj4gAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAANzSURBVHic7ZvNaxNBGIef2Ww3NWlL0IqKRYqgKOIHUm3Fi1bQarGi/4GgeFEP4rXiwYuXHvSkHrx5EsSKB73oraUFBT2IgqgFUVs/WxvzsdnxsE2bpMGk7e6+mOwDC/Nu9uX97S+Z2ckyo7TW1DNmYaD2n2yME0tIiQmCaZI/9ZPbqXystNbE9p/v0gYDwC5KTKlBbGBUOVxIPrk2rGL7znY4hhqi9m+8FNtw9J5IZH3nbWCDtBoBDK1Uuwl05c80Wg3s3twuJ8kHpjIOtjZm49dv35PJZvNhlwm05KNVy5t5NHAuWIU+M/Qxw2/Hmo1PXbzM+Nfv+bDFKJtVQ0Qt65+f17wBlQgNkBYgTWiAtABpQgOkBUgTGiAtQJrQAGkB0oQGSAuQJjRAWoA0oQHSAqQJDZAWIE1ogLQAaUIDpAVIExogLUCa0ABpAdLUvgEVlgAVLYqYTmW4+/SZr3qC5s03m5Seu810OlP0uWrsPlfXq6RqvwtUoO4NMDt3bhuKWg3xqGXFzYZIkxUx4xHTjCuFkhbnB5+mITfT6dOp9Lh6PFZfKyVHv0AqNxfXfReoewPEFkdGFKyOue3Pybl+GTRiBmxMQFuT226x4NUPGR1iXSARLd8OGjEDxqbA0e4xNiWlwu0CkxSsFg2Kj9Mw/sdtZ53g6trFY82kAQwHV76YrBPszadzYBfXGzYcRT+QK59SW7ybLApzjkO/caiNERQHUDwHAvw+giNlu0+ZCbfLOSie43Dgyh5GVOGeocGvNFu/WeFl8YdjHJxIcmMxuStjnOldx+Ol1H+XJjr+g3Q+Tub4dnUvs8Nu0Tygr5UpWvF0TL7+gt2LneP8yrKrp52bXuopxffHoIaexeYqzWEvtZTDVwOO3mO7oVhrKKj2KPkPvrb3Plv91OjrVHjHGnrUAt8qTPyBDwWjtVYcBl56KqwAX38BSnF8oTmJKJSYdsIzQWXwzYD+EbqBzoXmNRiwclnRqc4jD+j2SlcpvhhwaZQtSnNnsfltTejmwq0+mjs9D9jigbR5qGr2DvcOcgE4reeNUfOImIqEabActTRzNWgnRyrr1oyi0cB34CcVZq4KNHDrYR8DlepUOwh2aNhUzYW2BtubibUClpXErTPHP9HuxR1VFal29/ixQVbZ5v/xpti00ff7+FLNtVUbUKv8Bbb35zVfJzJiAAAAAElFTkSuQmCC"
                  loading="lazy" alt="" class="h-6" />
              </div>
              <div class="grow">
                <h6 class="mb-1"><a href="#!">Basic Computer</a></h6>
                <p class="text-gray-500 dark:text-dark-500">Class 9</p>
              </div>
              <p class="text-red-500 shrink-0">26 Dec</p>
            </div>
            <div class="flex items-center gap-3">
              <div class="flex items-center justify-center rounded-md bg-red-500/15 size-12">
                <img
                  src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAACXBIWXMAAAHYAAAB2AH6XKZyAAAAGXRFWHRTb2Z0d2FyZQB3d3cuaW5rc2NhcGUub3Jnm+48GgAACP5JREFUeJzlm3uMVNUZwH/fvXd2ZtgHLItEFlaFgqhbSmyNGP8jVbQoDWkqWP+hrS0rVWO1Kka0DJvGNmkqrdUKYtW0tSmrSSMUU5um2jS2lkCtqSu4IA/XfbDOPmZ2dud9v/6xsO6dmZ2ZOzO7K/GXbDL3nO9xzse9597znQ9hAtratgKbvcA8hLtl+4a95EEDL60BvQdYxf86h0mkIghtwM9l39bhfLozxebdugLG5ijK3Yaj1+ZR4HLgApQn8hnSwEuPg74GrAUasIwm0M+j2orqYb3px0umaA7lMj5HFZ4wMjqDE35/PJkF3fHS3aD3OhoFc8LVMgzZp7cEqsod7RTgmKPl6PImtxG3FIx5GNKaS1sDz/tQ3VGEo2aivk3AntLHWnnMNNtsAwXm2Qat4taA7mi7HuUvWR1Hu2Akntm6X/Y/9NXShjo9ZD4CRSCLXAhf5N7+9GIVFsliYIpkAfjPsZ4LxNJ7gYZc/cH+SLVt60hmuypBr8nO1VctD+bSmwz3AdDkm2ClitIVed2tebH0d8CayfrttObWAxJpvgh8xY0/14+ABG4Lgj5ThOggaj7t1j5waQk651juVqGENQBg1gPAmxmN9oTfUdBbZf/9rm5HAEFbgWQJg0qK5H5z5fdXIhp43gfVjwDfA+p598Ne4qn5KH8D7pc/PfROqbbb2/tqai+s+oWIfDvDa6pvcERU1XS02hwYiPZvvGHlyqy1oRAlB2DceeB1C85cxomeWoajHfLHh/vLtQnQMzj8SxW9K7P9zGAkW1h4+cpLGm/JbL73cfWPVHODYXN81xZ5N5efUt4CTt+B1Skgp/Fc6PqdcxgKReSNQKpc34UYqeavwLW2Qbpll968+w75c6ZMiWtAaei6n+wkHR+k1nda1z122VT6uvNJbQCuPXtp2gZrc8kVDIC2vrxKA3vXalubWUg2r531O+cA3z972QhGSzn2CvHUXdKvMr5Qp4EDueTyBkADbZux7bdADvAevylrREOhCNA9oeVoWfaKoCbC9aKsV2Hlns3yWi6ZQmvAukl+u0beCKR03WNfPvsvf5QvxfawvxyLhdl5n0SBV/LJ5A+A6D5Ubj57kddQMcj+h48CY9voKZ58seR9BGT7xj3AKkTWcoV+c3qGNL0UfA1KYMPB6RjITDGtr8FPI5/5AFjdw8OXk+YRsOeXYygYio6m1Z7lRkdVDoeIBVYvXhwrx7cbNu/Wi4BdwDzD5kGLtO4FVlRgWzCEMseNgqDXzVFfP/DTzD4V9RZtSMVN8vVHnM0Z2AYvGCK5My9uEWF2aYr2FZP01BdvRIsOvAqOjIoBUkxyowik7FvIgdLownXRsp4kj6jwqgoHVdgknZ3qN2sjR0HLSmD2h6Ok0ukSNPWFKxcv/JajRVV6QsNDQF2mdM7tMNhVo9bs5ub5OTvzYTQ1SRSxt7lVnErODEaayTH5PBjxmsSqUnwZAAvq6l5Es1JcM4ZtqOuzBLGNm0vxZQCIiNomDwC5U67TiKoKwm3uNWVDe3u766O48Q+hRXV1/wL5g3vHlaUnHF6D0uxeUxvj/vqNbrUcX4I28mv3jiuMytZSVUXY2paRMC2EIwCmainLeMXoGhq6HlhdhonmS0/2fMONguu9gBFPYkQToJnLRfZxlXsM13n9TNSQ7YcOqadY+YLbYc/HYXwdvXhPBbGGRpDkJzeJXe0lsXAusYsb1Kj39+H3Li7WsdhK/cETkXn/OBoD6BoIrxO4plj9SVFdas7t2QQ8W4x4zgBI2sbX0Uv14ZN4gpNXuhgjcXwdPfg6eqTuvU4rfNnCVP81S63IsgtRK/fNZY4kqD98gvmvv5es6o/UAL6xD5/wjgrsR85OgEePHTv222XLlmWd12fiCIDVG1T/8WDY395VZ8QSrnwaadsz5+1T1py3T2F7LaIL5xKfX0tqlg8jkcIzNELV0Ci+7kHEVoDx27Q3FPk6yJWuHObnohFz1neBJwsJOgIwb+8hAXXzBfYJYvjGss9gxFNUn+ij+kRfEXoiSlEVJ65QkYcPdXc/d1Vj42g+ucolRAz8pailFs1dwljRUqVZYMbkzkJClQuASPH79wmkGuu/ULExZCK69a1j/XnvaGcAEkkP8SQ5/xJTc5SnVVZpeYTiaKgyE/fkE3C+Bd7tTGW/3yfg88DyRrDKOiWbVkT0B+2dnU81NzXlLNfJKJS082+GYkkYcL3lnmlmJ5LmfZN1uj8e905N7aNtQ1fE4nTIYjBmEE0JtkJtlVLntbm4LsWiuhIfQ4N7Dhzse/Kmq+f3ZnY5A9BQb2LYuQugBKjxweySFvvJCcc6/3umqumdviriqewPoXAcuoZNjgQ9mCaj/pCn99ploSV+r53DmBMFTvf50n8/Ujc4GjcfBLLuBOdEFzekQcsumnDDyaiv5mB3cS+QdJpZHd2+Jce7vfaSC2PG8gVRFsxN4DGdT24oanG6z8uRLn96IOIxgSZgyx1P6892bZGuibLTOtlcJDweF9nfMWzEON7r53ivHxGo9aXxV9kk08Jo3CCWHF/aJq7WPttgG2M1TeOc9ydDqhCOmpwJeRiIWBMnn4vvbPmVOqrYnXfAR0FI2TFEfFmq59aAuTUVGPaM4UmbbANuP9fgDFfvEPQP+wiGyfr7OAwn+yCU99P6fGDT5t06Xp/k/hGIl1LD+KnCVOGH5y6cAZACAfF7oP68fgQAEGVjyy5dCZlrwIqLzUk/hUXAY1UsZzHDGMB24GvOAFR5kp+Co4FpQYX1Lbt05Xn/GiwDUWHTZzkAADd+1gPQ6AyAkS8ZMEXM7KIaz8wHVKTU3Q0jPldlRZWm3RmABaH3gcESjRXen+agq7H4QpAp4PeOAEhLSxJKLIq21XWl13BNLccumbH/Yfth9Qgv5loEW4ETrs2puwAowoHr1pCyZmRHHgM27LxPolkBkMCGAeAG4Igrk6rRYkVTlsUrN67l/SVLXbmoEB/ZBmueaZF/Q541WJ941cvAcAvIrUDhmp13Tn1Ayv7cpPbESA/Onm1+cMli/nnV1QzVlZYN7+4oSa0feB94OWXx7HO3y/iB5/8BKc8ilxjCjHgAAAAASUVORK5CYII="
                  loading="lazy" alt="" class="h-6" />
              </div>
              <div class="grow">
                <h6 class="mb-1"><a href="#!">Science Part 2 Test</a></h6>
                <p class="text-gray-500 dark:text-dark-500">Class 11</p>
              </div>
              <p class="text-red-500 shrink-0">21 Dec</p>
            </div>
            <div class="flex items-center gap-3">
              <div class="flex items-center justify-center rounded-md bg-red-500/15 size-12">
                <img
                  src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAB2AAAAdgB+lymcgAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAVQSURBVHic7VtLbBtFGP5mdr2OnTjvBwmtyAO1EU0kHoEiUFVQqYRSCkJCgjNCRMAJOCAuqBdQj1x66S2XSqhSJS5VKYqoAIGipMDBQoTSpkSkIVaaxHHs2OvdGQ5ObCddz6ztWW9C8kk+zM7sv/988z9mxjMEZYDzcxTT86+DkDcBPAugC0AaPWgsR44QKfyKVTyhTB7wKRm5eL5Upe5WCp967yhu3rsEQp7cUZWpWDUnEASVypPAFQH8l7ERMPtbAM0O1USpRoTUAVypSBGorAGffrcdjH8N586r7j4AVqdaoghSAgD+GYAeQQPFFJDdQwD/6cMQQN4WSuDKbaCmMUBsAUbyBIB6iQzFMQA1tQBJECT90oBECFQErXiyEbP/HkYsFtGQSOBw2woGH1oAId4GRFkWaJJK4LwqC1hY7sTVyVO49U8fOAgSSRtz82kAQFvDOt46PomXh6OeESEhwEXnSOUu8NvtY7h84ywspjnW319vwIWJU5ie7cMnZ67C0K1KP1USLrKABBV2/9Z8H7668eoDnXca6ck7/fjy+unKPiSB65lgSVSQBSym4coPo2CswL8RMHFiaAod4QUszhm4Fh3CX4td+frvZ47ihcEZPNN/p2qVi1E9ARXYQHR2EMuJwrwqEl7H+2fH0dq4CqQBNAOnh6K4MPESrkeP5dtdufmUcgKqd4EKLOCPuUe3lV977ptc54ugUY6xF79DRySRf/b7fDdSplGhos7wJQasFI0+pQyPPfKnY7ugbmGk926+zDhFbE3dwhNQQUAFFKSzhVE09Cw0ykq2ra/bvthMZdRagC8xYOfrG2Zh8sdNgKULmSBrO6dIVaiegCq7nzYNnBv/uFBOZ3B7Tn2+LwVfXECErK3CKN3Dlyywm+C7C1DKMND9d76cSjK0aFmQzQXWQrwZi3G1kb8YtbU3Bxh6Fu+MXio8SANYLhTHf3wel6ee9uz7KmLAnsYBAX4r4Df2PQG+BMEzxyeQNnN7n6JpMACcHJzBQGcsXz7UuqJUF18IOHLI/ZK2t30Jve1Lnumy713ggAC/FfAbBwT4rYDfULEr7K6NONvlULz3wTk4l79EaHUbJipWgxyyNeEagKRETgBAR6HIzBSs1GrJ5lswWh6WthFBhQvU7jSDB1CxIbKnCajNTNCAPAbsdGVNBzXCHilUgAoC5BYQ2vyVAaoHQXXvz0ocxAC/FfAbtXGBDQCmpA0FECkSapmwzZRUtB52PrzmFrUhwIS7eUAxAXYWLCN7CUCVBOx7FziYByiQIScgAkCW0ncMBTVCCOhq/wl2gqq1gBgU5dsaoSCa9x6qwgVUw826URlUnBBRTYE89ynELpwJkj1GgOoswPkeI0A9akpAWVmAEwN2oAecFKUnoqc4p4uEmL3lynsQNG7ShliWppoorG7w6gaIQ49xxoRXetxdmSEhpBpHkQmPgJPAzuquzZ8KNG3AOrlE0rktturPnnRygiDwUckGUgI4DSPe/gFsXVUfxSC58/fKENCJ6LaL3MTWm96oWee9QECjR0T1QgswgwOtZmh427OgQeHlBI1SoMlSNwWmoMJ/YoUEWMHhvmJHrAtS1Nd5eyhM0ygYU7oGmBNVCsfSDA1Hi8s63dMLP0cICWBa8zYCMlmA/884ELoAzVgTdlBLYvPmWNbiWE3YuRjgkSdsZGwsx2X7Z+5hBOjjonohAS19LatL99Y+B/DF1jPGAWYr0s4BlgVksuo+QEmJG69b9TIBbd2R8wS4qEyjGiNjsZ9F9VICCCG8radxjBP+CgeuAbivTDsHcHAXO6HuYdtcOBX+D2ObcoZK0AxuAAAAAElFTkSuQmCC"
                  loading="lazy" alt="" class="h-6" />
              </div>
              <div class="grow">
                <h6 class="mb-1"><a href="#!">English Grammar</a></h6>
                <p class="text-gray-500 dark:text-dark-500">Class 12</p>
              </div>
              <p class="text-red-500 shrink-0">15 Dec</p>
            </div>
            <div class="flex items-center gap-3">
              <div class="flex items-center justify-center rounded-md bg-primary-500/15 size-12">
                <img
                  src="data:image/png;base64,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"
                  loading="lazy" alt="" class="h-6" />
              </div>
              <div class="grow">
                <h6 class="mb-1"><a href="#!">Science Part 1 Test</a></h6>
                <p class="text-gray-500 dark:text-dark-500">Class 11</p>
              </div>
              <p class="text-red-500 shrink-0">03 Dec</p>
            </div>
            <div class="flex items-center gap-3">
              <div class="flex items-center justify-center rounded-md bg-purple-500/15 size-12">
                <img
                  src="data:image/png;base64,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"
                  loading="lazy" alt="" class="h-6" />
              </div>
              <div class="grow">
                <h6 class="mb-1"><a href="#!">Management</a></h6>
                <p class="text-gray-500 dark:text-dark-500">Class 12</p>
              </div>
              <p class="text-red-500 shrink-0">24 Nov</p>
            </div>
          </div>
        </div>
        <div class="order-11 col-span-12 2xl:col-span-9 card" x-data="emailsTable()">
          <div class="flex items-center gap-3 card-header">
            <h6 class="card-title grow">Top Score</h6>
            <div class="shrink-0">
              <a href="#!" class="py-1 px-2.5 btn btn-primary">View All
                <i data-lucide="move-right" class="ml-1 ltr:inline-block rtl:hidden size-4"></i>
                <i data-lucide="move-left" class="mr-1 rtl:inline-block ltr:hidden size-4"></i></a>
            </div>
          </div>
          <div class="pt-0 card-body">
            <div class="overflow-x-auto table-box">
              <table class="table whitespace-nowrap flush">
                <tbody>
                  <template x-for="(email, index) in displayedEmails" :key="index">
                    <tr>
                      <td x-text="email.studentsName"></td>
                      <td><span x-text="email.marks"></span>/600</td>
                      <td x-text="email.resultDate"></td>
                      <td x-text="email.grade"></td>
                      <td>
                        <span x-text="email.passFail" :class="{
                                            'badge badge-green': email.passFail === 'Pass',
                                            'badge badge-orange': email.passFail === 'Fail',
                                        }"></span>
                      </td>
                    </tr>
                  </template>
                </tbody>
              </table>
            </div>
            <div class="grid items-center grid-cols-12 gap-space mt-space">
              <div class="col-span-12 text-center md:col-span-6 ltr:md:text-left rtl:md:text-right">
                <p class="text-gray-500 dark:text-dark-500">
                  Showing <b x-text="showingStart"></b> -
                  <b x-text="showingEnd"></b> of
                  <b x-text="emails.length"></b> Results
                </p>
              </div>
              <div class="col-span-12 md:col-span-6">
                <div class="flex justify-center md:justify-end pagination pagination-primary">
                  <button @click="prevPage()" :disabled="currentPage === 1" class="pagination-pre">
                    <i data-lucide="chevron-left" class="mr-1 ltr:inline-block rtl:hidden size-4"></i>
                    <i data-lucide="chevron-right" class="ml-1 rtl:inline-block ltr:hidden size-4"></i>
                    Prev</button><template x-for="page in totalPages" :key="page"><button @click="gotoPage(page)"
                      :class="{ 'active': currentPage === page }" class="pagination-item">
                      <span x-text="page"></span></button></template><button @click="nextPage()"
                    :disabled="currentPage === totalPages" class="pagination-next">
                    Next
                    <i data-lucide="chevron-right" class="ml-1 ltr:inline-block rtl:hidden size-4"></i>
                    <i data-lucide="chevron-left" class="mr-1 rtl:inline-block ltr:hidden size-4"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="order-12 col-span-12 xl:col-span-8 2xl:col-span-9 card">
          <div class="card-header">
            <h6 class="card-title">
              Continue Watching
              <i data-lucide="move-right" class="ml-1 ltr:inline-block rtl:hidden size-4"></i>
              <i data-lucide="move-left" class="mr-1 rtl:inline-block ltr:hidden size-4"></i>
            </h6>
          </div>
          <div class="card-body">
            <div class="grid grid-cols-12 gap-space">
              <div class="col-span-12 lg:col-span-4">
                <div class="aspect-video">
                  <iframe class="w-full rounded-md aspect-video"
                    src="https://www.youtube.com/embed/_x9lsSPW4rA?si=dldJWZYL7u6URBi5" title="YouTube video player"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
                <div class="mt-3">
                  <h6 class="mb-1 truncate">
                    <a href="#!">Domiex - Admin & Dashboard Template Introduction</a>
                  </h6>
                  <p class="text-xs text-gray-500 dark:text-dark-500">
                    Domiex Intro
                  </p>
                </div>
              </div>
              <div class="col-span-12 lg:col-span-4">
                <div class="aspect-video">
                  <iframe class="w-full rounded-md aspect-video"
                    src="https://www.youtube.com/embed/mSC6GwizOag?si=Dqcl3RgGrfRyqmHo" title="YouTube video player"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
                <div class="mt-3">
                  <h6 class="mb-1 truncate">
                    <a href="#!">What's new in Tailwind CSS v3.0?</a>
                  </h6>
                  <p class="text-xs text-gray-500 dark:text-dark-500">
                    TailwindCSS
                  </p>
                </div>
              </div>
              <div class="col-span-12 lg:col-span-4">
                <div class="aspect-video">
                  <iframe class="w-full rounded-md aspect-video"
                    src="https://www.youtube.com/embed/RZ9cWr3tY9w?si=J6KavpQC6n9gaC64" title="YouTube video player"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
                <div class="mt-3">
                  <h6 class="mb-1 truncate">
                    <a href="#!">Controlling Stacking Contexts with Isolation
                      Utilities</a>
                  </h6>
                  <p class="text-xs text-gray-500 dark:text-dark-500">
                    TailwindCSS
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-span-12 text-center bg-primary-500 order-13 text-primary-50 xl:col-span-4 2xl:col-span-3 card">
          <div class="card-body">
            <h5 class="mb-2">
              Join the community and find out more information
            </h5>
            <button type="button" class="btn btn-green">Explore Now</button>
            <div class="mt-5">
              <img src="assets/images/school.png" loading="lazy" alt="" class="mx-auto h-44" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="main-footer">
      <div class="w-full">
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
          <div class="hidden lg:block">
            <ul class="flex items-center gap-4">
              <li>
                <a href="#!"
                  class="font-medium text-gray-500 transition duration-300 ease-linear dark:text-dark-500 hover:text-primary-500 dark:hover:text-primary-500">About</a>
              </li>
              <li>
                <a href="#!"
                  class="font-medium text-gray-500 transition duration-300 ease-linear dark:text-dark-500 hover:text-primary-500 dark:hover:text-primary-500">Support</a>
              </li>
              <li>
                <a href="#!"
                  class="font-medium text-gray-500 transition duration-300 ease-linear dark:text-dark-500 hover:text-primary-500 dark:hover:text-primary-500">Purchase
                  Now</a>
              </li>
            </ul>
          </div>
          <div class="text-center text-gray-500 dark:text-dark-500 ltr:lg:text-right rtl:lg:text-left">
            <div x-data="{ year: new Date().getFullYear() }">
              &copy; <span x-text="year"></span> Domiex. Crafted by
              <a href="#!" class="font-semibold">SRBThemes</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

        @endsection
        @section('scripts')
        @endsection