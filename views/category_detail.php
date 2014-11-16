    
    <h2>{{category:name}}</h2>

    <div class="row" itemscope itemtype="http://schema.org/Product">
        <div class="row category-list">
            {{ shop_categories:categories order="order" x="DESC" parent=category:id }}
                {{ if !hidden }}
                   <div class="col-sm-3">
                       <div class="category-item">
                            <div class="category-image"><img class="img-responsive" src='{{url:site}}files/large/{{file_id}}'></div>
                            <a href="{{ url:site }}shop/categories/category/{{slug}}"><h4>{{ name }}</h4></a>
                            <div class="category-description">{{ description }}</div>
                            <div class="buy-now"><a class="btn btn-adina" href="{{ url:site }}shop/categories/category/{{slug}}">BUY NOW</a></div>
                       </div>
                   </div>    <!-- end col-sm-3 -->                                                                 
                {{ endif }}     
            {{ /shop_categories:categories }}
        </div>  <!-- end row -->
    </div>

    <div class="category">
      

        {{ shop_categories:products limit="3" category=category:slug }}

         <div class="row the-product" itemscope itemtype="http://schema.org/Product">
            <div class="">
                    {{shop_images:cover product_id="{{id}}"}}
                            <img class="img-responsive" src="{{src}}/200" />
                    {{/shop_images:cover}}   
            </div>
            <div class="">
                <h3><a itemprop="url" href="{{ url:site }}shop/products/product/{{ slug }}">{{name}}</a></h3>
                <div class="item-description">{{ description }}</div>
                <div class="more-details">
                    {{shop_adina:if_description id='{{id}}'}}
                         <a href="{{ url:site }}shop/products/product/{{ slug }}"><button class="btn btn-default btn-sm"> MORE INFO </button></a>
                    {{/shop_adina:if_description}}
                </div>
            </div>
            <div class="col-sm-4">
                <form action="{{url:site}}shop/cart/add"  id="form_{{id}}" name="form_{{id}}" method="post" class="item-variances">

                    <div class="product-price">
                        {{shop:fromprice product="{{id}}" x="" fromtext="<span>from</span>" na="N/A" }}
                    </div>

                    {{shop:htmlvariances product='{{id}}' x='SELECT,PRICE' }}

                    <div class="item-action" >
                        <div>
                            <input class="item-qty" type='text' name='qty' placeholder='1'>
                        </div>

                        <button type='submit' class="btn btn-danger btn-sm">
                            ADD TO CART &nbsp; <i class="fa fa-shopping-cart fa-lg"></i>
                        </button>

                    </div>
                </form>


            </div> <!-- / col -->
        </div> <!-- /row -->    
        {{ /shop_categories:products }}

    </div>

    <div>


        <h2>More...</h2>
        <!--  Only display link if the current category has a parent category -->
        {{if category:parent_id > 0 }}
            {{shop_categories:category id="{{category:parent_id}}" }}
                <a class='btn'  href="{{url:site}}shop/categories/category/{{slug}}">&larr; back to  {{name}}</a>  |
            {{/shop_categories:category}}
        {{endif}}


        <!--  Static link for consistency -->        
        <a  class='btn'  href="{{url:site}}shop/categories/">all categories</a>     


        <!--  Helpful resource lin kto cart -->
        {{ shop:cart }}
            {{if item_count > 0}}
                | <a  class='btn'  href="{{url:site}}shop/cart/">view cart</a>    
            {{ endif }}
        {{ /shop:cart }}



        <!--  Displays link if the current catgeory has more than 3 products -->
        {{ if category:product_count > 3 }}
            | <a class='btnm' href="{{url:site}}shop/categories/products/{{category:slug}}">discover {{category:product_count}} more products in this category &rarr;</a>  
        {{ endif }}

         <br />        

     </div>