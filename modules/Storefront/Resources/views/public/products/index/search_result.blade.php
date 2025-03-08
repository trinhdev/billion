<div class="search-result">
    <div class="search-result-top">
        <div class="content-left">
            <template x-if="queryParams.query">
                <h4>
                    {{ trans('storefront::products.search_results_for') }}
                    
                    <span x-text="queryParams.query"></span>
                </h4>
            </template>

            <template x-if="!queryParams.query && queryParams.brand">
                <h4 x-text="initialBrandName"></h4>
            </template>

            <template x-if="!queryParams.query && !queryParams.brand && queryParams.category">
                <h4 x-text="categoryName"></h4>
            </template>
            
            <template x-if="!queryParams.query && !queryParams.brand && !queryParams.category && queryParams.tag">
                <h4 x-text="initialTagName"></h4>
            </template>
            
            <template x-if="!queryParams.query && !queryParams.brand && !queryParams.category && !queryParams.tag">
                <h4>{{ trans('storefront::products.shop') }}</h4>
            </template>
        </div>

        <div class="content-right">
            <div class="sorting-bar">
                <div class="mobile-view-filter" @click.stop="$store.layout.openSidebarFilter()">
                    <i class="las la-sliders-h"></i>
    
                    {{ trans('storefront::products.filters') }}
                </div>

                <div class="view-type">
                    <button
                        type="submit"
                        class="btn btn-grid-view"
                        :class="{ active: viewMode === 'grid' }"
                        title="{{ trans('storefront::products.grid_view') }}"
                        @click="viewMode = 'grid'"
                    >
                        <i class="las la-th-large"></i>
                    </button>

                    <button
                        type="submit"
                        class="btn btn-list-view"
                        :class="{ active: viewMode === 'list' }"
                        title="{{ trans('storefront::products.list_view') }}"
                        @click="viewMode = 'list'"
                    >
                        <i class="las la-list"></i>
                    </button>
                </div>

                <div class="mobile-view-filter-dropdown">
                    <div class="form-group">
                        <div
                            x-data="{
                                open: false,
                                selected: '{{ request('sort', 'latest') }}',
                                selectedText: '{{ ucfirst(request('sort', 'latest')) }}'
                            }"
                            class="dropdown custom-dropdown"
                            @click.away="open = false"
                        >
                            <div
                                class="btn btn-secondary dropdown-toggle"
                                :class="{ active: open }"
                                @click="open = !open"
                            >
                                <span x-text="selectedText">{{ ucfirst(request('sort', 'latest')) }}</span>
    
                                <i class="las la-angle-down"></i>
                            </div>
    
                            <ul
                                x-cloak
                                x-show="open"
                                x-transition
                                class="dropdown-menu"
                                :class="{ active: open }"
                            >
                                <div class="dropdown-menu-scroll">
                                    @foreach (trans('storefront::products.sort_options') as $key => $value)
                                        <li
                                            class="dropdown-item"
                                            :class="{ active: selected === '{{ $key }}' }"
                                            @click="
                                                open = false;
                                                
                                                if (selected !== '{{ $key }}') {
                                                    changeSort('{{ $key }}');
                                                }
        
                                                selected = '{{ $key }}';
                                                selectedText = $el.innerText;
                                            "
                                        >
                                            {{ $value }}
                                        </li>
                                    @endforeach
                                </div>
                            </ul>
                        </div>
                    </div>
    
                    <div
                        x-data="{
                            open: false,
                            selected: {{ request('perPage', 20) }}
                        }"
                        class="dropdown custom-dropdown"
                        @click.away="open = false"
                    >
                        <div
                            class="btn btn-secondary dropdown-toggle"
                            :class="{ active: open }"
                            @click="open = !open"
                        >
                            <span x-text="selected">{{ request('perPage', 20) }}</span>
    
                            <i class="las la-angle-down"></i>
                        </div>
    
                        <ul
                            x-cloak
                            x-show="open"
                            x-transition
                            class="dropdown-menu"
                            :class="{ active: open }"
                        >
                            @foreach (trans('storefront::products.per_page_options') as $key => $value)
                                <li
                                    class="dropdown-item"
                                    :class="{ active: selected === {{ $value }} }"
                                    @click="
                                        open = false;
                                        
                                        if (selected !== {{ $value }}) {
                                            changePerPage({{ $value }});
                                        }
    
                                        selected = {{ $value }};
                                    "
                                >
                                    {{ $value }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div
        class="search-result-middle"
        :class="{
            empty: emptyProducts,
            loading: fetchingProducts 
        }"
    >  
        <template x-if="!emptyProducts && viewMode === 'grid'">
            @include('storefront::public.products.index.grid_view_products')
        </template>

        <template x-if="!emptyProducts && viewMode === 'list'">
            @include('storefront::public.products.index.list_view_products')
        </template>
        
        <template x-if="!fetchingProducts && emptyProducts">
            <div class="empty-message">
                @include('storefront::public.products.index.empty_results_logo')

                <h2>{{ trans('storefront::products.no_product_found') }}</h2>
            </div>
        </template>
    </div>

    <template x-if="!emptyProducts">
        <div class="search-result-bottom">
            <span class="showing-results" x-text="showingResults"></span>

            <template x-if="products.total > queryParams.perPage">
                @include('storefront::public.partials.pagination')
            </template>
        </div>
    </template>
</div>
