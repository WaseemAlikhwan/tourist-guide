# Generates docs/tourist-guide-diagrams.drawio (full project diagrams)
$out = Join-Path $PSScriptRoot "..\docs\tourist-guide-diagrams.drawio"
$outLegacy = Join-Path $PSScriptRoot "..\docs\tourist-guide-class-diagram.drawio"
$outRoot = Join-Path $PSScriptRoot "..\tourist-guide-class-diagram.drawio"

function Esc([string]$s) { [System.Security.SecurityElement]::Escape($s) }

function Box($id, $parent, $x, $y, $w, $h, $title, $lines, $fill, $stroke) {
    $body = "&lt;b&gt;" + (Esc $title) + "&lt;/b&gt;&lt;hr size=&quot;1&quot;/&gt;" + (($lines | ForEach-Object { Esc $_ }) -join "&lt;br/&gt;")
    $val = "&lt;p style=&quot;margin:4px;font-size:11px;&quot;&gt;$body&lt;/p&gt;"
    $style = "rounded=0;whiteSpace=wrap;html=1;fillColor=$fill;strokeColor=$stroke;align=left;verticalAlign=top;spacingLeft=6;spacingTop=4;"
    @"
        <mxCell id="$id" value="$val" style="$style" vertex="1" parent="$parent">
          <mxGeometry x="$x" y="$y" width="$w" height="$h" as="geometry"/>
        </mxCell>
"@
}

function Edge($id, $parent, $src, $tgt, $label, $dashed = $false) {
    $dash = if ($dashed) { "dashed=1;" } else { "" }
    $lv = if ($label) { "value=`"$(Esc $label)`"" } else { "" }
    @"
        <mxCell id="$id" $lv style="endArrow=open;endFill=0;html=1;rounded=0;fontSize=10;${dash}" edge="1" parent="$parent" source="$src" target="$tgt">
          <mxGeometry relative="1" as="geometry"/>
        </mxCell>
"@
}

function PageStart($id, $name, $pw, $ph, $default = $false) {
    $script:defaultPageAttr = if ($default) { ' default="true"' } else { '' }
@"
  <diagram id="$id" name="$name"$defaultPageAttr>
    <mxGraphModel dx="1400" dy="900" grid="1" gridSize="10" guides="1" tooltips="1" connect="1" arrows="1" fold="1" page="1" pageScale="1" pageWidth="$pw" pageHeight="$ph" math="0" shadow="0">
      <root>
        <mxCell id="0"/>
        <mxCell id="1" parent="0"/>
"@
}

function PageEnd { @"
      </root>
    </mxGraphModel>
  </diagram>
"@ }

function ErEntity($id, $parent, $x, $y, $w, $h, $title, $cols) {
    Box $id $parent $x $y $w $h $title $cols '#f5f5f5' '#666666'
}

# --- Page 0 Overview / Architecture (default) ---
$pArch = PageStart "pArch" "٠ - نظرة عامة والبنية" 1200 900 $true
$pArch += (Box 'arch0' '1' 40 20 1120 55 'مخططات مشروع دليل السائح - Laravel 10' @(
    'ملف واحد يضم كل مخططات المشروع (UML + متطلبات + قاعدة بيانات)',
    'افتح التبويبات بالأسفل للتنقل بين المخططات'
) '#dae8fc' '#6c8ebf')
$pArch += (Box 'arch1' '1' 80 100 1040 70 'طبقة العرض (Presentation)' @(
    'Blade Views: website / admin / provider',
    'واجهات عربية/إنجليزية + تفاعل المستخدم'
) '#dae8fc' '#6c8ebf')
$pArch += (Box 'arch2' '1' 80 200 1040 90 'طبقة HTTP' @(
    'Controllers: Admin, User, Provider, Public',
    'Middleware: auth, isAdmin, approvedContentProvider, SetLocale'
) '#d5e8d4' '#82b366')
$pArch += (Box 'arch3' '1' 80 320 1040 90 'طبقة التطبيق (Application)' @(
    'Services: Activity, Destination, Hotel, Weather, Dashboard, ...',
    'Notifications, Jobs, Artisan Commands, Policies'
) '#fff2cc' '#d6b656')
$pArch += (Box 'arch4' '1' 80 440 1040 90 'طبقة المجال (Domain)' @(
    'Eloquent Models (21): User, Activity, Booking, Destination, ...',
    'Traits: HasLocalizedAttributes, UploadImageTrait'
) '#ffe6cc' '#d79b00')
$pArch += (Box 'arch5' '1' 80 560 1040 90 'البنية التحتية (Infrastructure)' @(
    'MySQL Database + Migrations/Seeders',
    'External: Weather API, Socialite OAuth, File Storage'
) '#f8cecc' '#b85450')
$pArch += (Edge 'ae1' '1' 'arch1' 'arch2' '')
$pArch += (Edge 'ae2' '1' 'arch2' 'arch3' '')
$pArch += (Edge 'ae3' '1' 'arch3' 'arch4' '')
$pArch += (Edge 'ae4' '1' 'arch4' 'arch5' '')
$pArch += (Box 'arch6' '1' 80 700 1040 120 'فهرس الصفحات' @(
    '١ النماذج | ٢ قاعدة البيانات ERD | ٣ الخدمات | ٤ المتحكمات | ٥ الإشعارات',
    '٦ البنية التحتية | ٧ المتطلبات (وظيفية/غير وظيفية) | ٨ حالات الاستخدام | ٩ الدليل'
) '#f5f5f5' '#666666')
$pArch += PageEnd

# --- Page 9 Guide (shown last) ---
$p0 = PageStart "p0" "٩ - دليل الاستخدام" 1100 850
$p0 += @"
        <mxCell id="t1" value="&lt;h1 style=&quot;margin:0;&quot;&gt;دليل السائح - دليل المخططات&lt;/h1&gt;&lt;p&gt;Tourist Guide - Laravel 10&lt;/p&gt;" style="text;html=1;strokeColor=none;fillColor=none;align=center;fontSize=18;" vertex="1" parent="1">
          <mxGeometry x="120" y="30" width="860" height="70" as="geometry"/>
        </mxCell>
        <mxCell id="t2" value="&lt;b&gt;كيف تفتح الملف&lt;/b&gt;&lt;hr/&gt;1) draw.io / diagrams.net&lt;br/&gt;2) File - Open from - Device&lt;br/&gt;3) اختر: docs/tourist-guide-diagrams.drawio&lt;br/&gt;4) استخدم التبويبات أسفل الشاشة" style="rounded=1;whiteSpace=wrap;html=1;fillColor=#dae8fc;strokeColor=#6c8ebf;align=left;verticalAlign=top;spacingLeft=12;spacingTop=10;fontSize=13;" vertex="1" parent="1">
          <mxGeometry x="80" y="120" width="940" height="110" as="geometry"/>
        </mxCell>
        <mxCell id="t3" value="&lt;b&gt;قائمة المخططات (١٠ صفحات)&lt;/b&gt;&lt;hr/&gt;٠ - نظرة عامة والبنية المعمارية&lt;br/&gt;١ - مخطط الفئات (Class Diagram) - 21 Model&lt;br/&gt;٢ - مخطط قاعدة البيانات (ERD)&lt;br/&gt;٣ - طبقة الخدمات والمهام&lt;br/&gt;٤ - المتحكمات (Controllers)&lt;br/&gt;٥ - الإشعارات (Notifications)&lt;br/&gt;٦ - البنية التحتية (Middleware/Requests/Policies)&lt;br/&gt;٧ - المتطلبات الوظيفية وغير الوظيفية&lt;br/&gt;٨ - مخطط حالات الاستخدام (Use Case)&lt;br/&gt;٩ - هذا الدليل" style="rounded=1;whiteSpace=wrap;html=1;fillColor=#f5f5f5;strokeColor=#666666;align=left;verticalAlign=top;spacingLeft=12;spacingTop=10;fontSize=13;" vertex="1" parent="1">
          <mxGeometry x="80" y="250" width="940" height="220" as="geometry"/>
        </mxCell>
        <mxCell id="t4" value="&lt;b&gt;التدفق الرئيسي للمجال&lt;/b&gt;&lt;hr/&gt;مستخدم - حجز (Booking) - نشاط (Activity) - وجهة (Destination)&lt;br/&gt;مزود محتوى يدير الأنشطة بعد الاعتماد (provider_review_status)&lt;br/&gt;المفضلة والمعرض: علاقات Polymorphic" style="rounded=1;whiteSpace=wrap;html=1;fillColor=#fff2cc;strokeColor=#d6b656;align=left;verticalAlign=top;spacingLeft=12;spacingTop=10;fontSize=13;" vertex="1" parent="1">
          <mxGeometry x="80" y="490" width="940" height="90" as="geometry"/>
        </mxCell>
        <mxCell id="t5" value="&lt;b&gt;تحديث المخططات&lt;/b&gt;&lt;hr/&gt;powershell -ExecutionPolicy Bypass -File scripts/build-drawio-diagram.ps1" style="rounded=1;whiteSpace=wrap;html=1;fillColor=#d5e8d4;strokeColor=#82b366;align=left;verticalAlign=top;spacingLeft=12;spacingTop=10;fontSize=13;" vertex="1" parent="1">
          <mxGeometry x="80" y="600" width="940" height="70" as="geometry"/>
        </mxCell>
"@
$p0 += PageEnd

# --- Page 1 Models ---
$p1 = PageStart "p1" "١ - النماذج (Class Diagram)" 1600 1300
$p1 += @"
        <mxCell id="banner" value="&lt;b style=&quot;font-size:16px;&quot;&gt;مخطط الفئات - نماذج Eloquent (21 صنف)&lt;/b&gt;&lt;br/&gt;التكبير/التصغير: Ctrl + عجلة الفأرة | بقية الصفحات من التبويبات بالأسفل" style="text;html=1;strokeColor=#6c8ebf;fillColor=#dae8fc;align=center;verticalAlign=middle;rounded=1;fontSize=12;" vertex="1" parent="1">
          <mxGeometry x="40" y="10" width="1320" height="50" as="geometry"/>
        </mxCell>
"@
$models = @{
    User = @{ x=480; y=40; h=200; fill='#fff2cc'; stroke='#d6b656'; lines=@(
        'extends Authenticatable','---','id, name, email, role','is_content_provider','content_provider_status','can_login','---','+ favorites() HasMany','+ bookings() HasMany','+ badges() BelongsToMany','+ usedCoupons() BelongsToMany','+ providedActivities() HasMany','+ contentProviderApplications()','+ latestContentProviderApplication() HasOne'
    )}
    Destination = @{ x=40; y=280; h=175; fill='#dae8fc'; stroke='#6c8ebf'; lines=@(
        'uses HasLocalizedAttributes','---','name_ar/en, lat, lng','---','+ activities() HasMany','+ hotels() / activeHotels() HasMany','+ weather() HasOne','+ weatherHistory() HasMany','+ favorites() MorphMany','+ gallery() MorphMany'
    )}
    Activity = @{ x=400; y=280; h=200; fill='#dae8fc'; stroke='#6c8ebf'; lines=@(
        'uses HasLocalizedAttributes','---','destination_id, provider_id','price, provider_review_status','requires_booking, event_date','---','+ destination() BelongsTo','+ provider() BelongsTo','+ reviews()/approvedReviews()','+ comments()/approvedComments()','+ bookings() HasMany','+ favorites(), gallery() MorphMany','+ associations() HasMany'
    )}
    Booking = @{ x=780; y=280; h=160; fill='#fff2cc'; stroke='#d6b656'; lines=@(
        '---','user_id, activity_id','status, payment_status','total_price, booking_reference','---','+ user(), activity() BelongsTo','+ couponUsages() HasMany'
    )}
    Hotel = @{ x=40; y=520; h=120; fill='#dae8fc'; stroke='#6c8ebf'; lines=@('destination_id','name_ar/en, star_rating','+ destination() BelongsTo')}
    Review = @{ x=280; y=520; h=110; fill='#f8cecc'; stroke='#b85450'; lines=@('user_id, activity_id','rating, is_approved','+ user(), activity()')}
    Comment = @{ x=480; y=520; h=110; fill='#f8cecc'; stroke='#b85450'; lines=@('user_id, activity_id','comment, is_approved','+ user(), activity()')}
    Favorite = @{ x=680; y=40; h=120; fill='#e1d5e7'; stroke='#9673a6'; lines=@('user_id','favoritable_id/type','+ user() BelongsTo','+ favoritable() MorphTo')}
    Gallery = @{ x=900; y=40; h=110; fill='#e1d5e7'; stroke='#9673a6'; lines=@('galleryable_id/type','image_path, order','+ galleryable() MorphTo')}
    Weather = @{ x=40; y=700; h=100; fill='#dae8fc'; stroke='#6c8ebf'; lines=@('destination_id','temperature, condition','+ destination()')}
    Coupon = @{ x=200; y=700; h=115; fill='#fff2cc'; stroke='#d6b656'; lines=@('code, discount_type','+ users() BelongsToMany')}
    CouponUser = @{ x=400; y=700; h=115; fill='#f5f5f5'; stroke='#666666'; lines=@('pivot coupon_user','coupon_id, user_id, booking_id','+ coupon(), user(), booking()')}
    Badge = @{ x=620; y=700; h=100; fill='#fff2cc'; stroke='#d6b656'; lines=@('name, slug, icon','+ users() BelongsToMany')}
    ContentProviderApplication = @{ x=280; y=40; h=130; fill='#fff2cc'; stroke='#d6b656'; lines=@('user_id, admin_id','status, activity_type','+ user(), admin()')}
    ActivityAssociation = @{ x=780; y=40; h=130; fill='#dae8fc'; stroke='#6c8ebf'; lines=@('activity_id','associated_activity_id','support, confidence','+ activity(), associatedActivity()')}
    TravelBasic = @{ x=780; y=520; h=115; fill='#dae8fc'; stroke='#6c8ebf'; lines=@('uses HasLocalizedAttributes','title_ar/en, order','scopeActive(), scopeOrdered()')}
    Contact = @{ x=40; y=860; h=100; fill='#f5f5f5'; stroke='#666666'; lines=@('name, email, message','status')}
    TravelBasicCategory = @{ x=220; y=860; h=70; fill='#f5f5f5'; stroke='#666666'; lines=@('minimal class (extends Model)')}
    TravelPage = @{ x=400; y=860; h=70; fill='#f5f5f5'; stroke='#666666'; lines=@('minimal class (extends Model)')}
    UserRecommendation = @{ x=580; y=860; h=70; fill='#f5f5f5'; stroke='#666666'; lines=@('minimal class (extends Model)')}
    Setting = @{ x=760; y=860; h=70; fill='#f5f5f5'; stroke='#666666'; lines=@('minimal class (extends Model)')}
}
$mid = @{}
$i = 10
foreach ($name in $models.Keys) {
    $m = $models[$name]
    $cid = "m$i"
    $mid[$name] = $cid
    $p1 += (Box $cid "1" $m.x ($m.y + 60) 220 $m.h $name $m.lines $m.fill $m.stroke)
    $i++
}
$ei = 100
$edges = @(
    @('Destination','Activity','1','*',$false),
    @('User','Activity','1','* (provider)',$false),
    @('User','Booking','1','*',$false),
    @('Activity','Booking','1','*',$false),
    @('User','Review','1','*',$false),
    @('Activity','Review','1','*',$false),
    @('User','Comment','1','*',$false),
    @('Activity','Comment','1','*',$false),
    @('Destination','Hotel','1','*',$false),
    @('Destination','Weather','1','*',$false),
    @('Destination','Weather','1','1 latest',$false),
    @('User','Favorite','1','*',$false),
    @('Activity','Favorite','morph 1','*',$true),
    @('Destination','Favorite','morph 1','*',$true),
    @('Activity','Gallery','morph 1','*',$true),
    @('Destination','Gallery','morph 1','*',$true),
    @('User','Badge','*','* badge_user',$false),
    @('User','Coupon','*','* coupon_user',$false),
    @('Booking','CouponUser','1','*',$false),
    @('CouponUser','Coupon','*','1',$false),
    @('CouponUser','User','*','1',$false),
    @('User','ContentProviderApplication','1','*',$false),
    @('User','ContentProviderApplication','1','1 latest',$false),
    @('Activity','ActivityAssociation','1','*',$false),
    @('ActivityAssociation','Activity','*','1 associated',$false)
)
foreach ($e in $edges) {
    $p1 += (Edge "e$ei" "1" $mid[$e[0]] $mid[$e[1]] "$($e[2]) -- $($e[3])" $e[4])
    $ei++
}
$p1 += (Box 'leg1' '1' 40 1180 1500 80 'دليل الألوان' @(
    'أزرق: محتوى سياحي | أصفر: مستخدم/حجز | أحمر: تفاعل | بنفسجي: Polymorphic | رمادي: جداول مساعدة',
    'خط متصل: Eloquent | خط متقطع: MorphMany/MorphTo'
) '#f5f5f5' '#666666')
$p1 += PageEnd

# --- Page 2 ERD ---
$pErd = PageStart "pErd" "٢ - قاعدة البيانات (ERD)" 1800 1100
$pErd += (Box 'erdTitle' '1' 40 15 1720 50 'مخطط علاقات الكيانات (ERD) - MySQL' @(
    'الجداول الرئيسية والمفاتيح الأجنبية (FK) - مستخلص من migrations'
) '#dae8fc' '#6c8ebf')
$erd = @{
    users = @{ x=40; y=90; h=130; cols=@('PK id','name, email, password','role, is_content_provider','content_provider_status') }
    destinations = @{ x=280; y=90; h=120; cols=@('PK id','name_ar, name_en','latitude, longitude','province') }
    activities = @{ x=520; y=90; h=140; cols=@('PK id','FK destination_id','FK provider_id -> users','price, provider_review_status','is_event, event_date') }
    hotels = @{ x=760; y=90; h=110; cols=@('PK id','FK destination_id','name_ar/en, star_rating','is_active') }
    bookings = @{ x=1000; y=90; h=130; cols=@('PK id','FK user_id','FK activity_id','status, payment_status','total_price, booking_reference') }
    reviews = @{ x=1240; y=90; h=100; cols=@('PK id','FK user_id','FK activity_id','rating, is_approved') }
    comments = @{ x=1480; y=90; h=100; cols=@('PK id','FK user_id','FK activity_id','comment, is_approved') }
    favorites = @{ x=40; y=280; h=110; cols=@('PK id','FK user_id','favoritable_id','favoritable_type (morph)') }
    galleries = @{ x=240; y=280; h=110; cols=@('PK id','galleryable_id/type (morph)','image_path, order') }
    weather = @{ x=440; y=280; h=100; cols=@('PK id','FK destination_id','temperature, condition') }
    coupons = @{ x=620; y=280; h=100; cols=@('PK id','code, discount_type','discount_value, is_active') }
    coupon_user = @{ x=820; y=280; h=110; cols=@('PK id','FK coupon_id','FK user_id','FK booking_id','used_at') }
    badges = @{ x=1020; y=280; h=90; cols=@('PK id','name, slug, icon') }
    badge_user = @{ x=1180; y=280; h=90; cols=@('FK badge_id','FK user_id','earned_at') }
    contacts = @{ x=1340; y=280; h=100; cols=@('PK id','name, email','message, status') }
    travel_basics = @{ x=1520; y=280; h=100; cols=@('PK id','title_ar/en','order, is_active') }
    content_provider_applications = @{ x=40; y=440; h=120; cols=@('PK id','FK user_id','FK admin_id','status, activity_type') }
    activity_associations = @{ x=300; y=440; h=110; cols=@('PK id','FK activity_id','FK associated_activity_id','support, confidence, lift') }
    notifications = @{ x=560; y=440; h=90; cols=@('id (uuid)','type, notifiable','data, read_at') }
}
$eid = 10
$eids = @{}
foreach ($t in $erd.Keys) {
    $e = $erd[$t]
    $cid = "erd$eid"
    $eids[$t] = $cid
    $pErd += (ErEntity $cid '1' $e.x $e.y 200 $e.h $t $e.cols)
    $eid++
}
$erdEdges = @(
    @('destinations','activities'),@('users','activities','provider'),@('destinations','hotels'),
    @('users','bookings'),@('activities','bookings'),@('users','reviews'),@('activities','reviews'),
    @('users','comments'),@('activities','comments'),@('users','favorites'),
    @('destinations','weather'),@('users','content_provider_applications'),@('users','coupon_user','admin'),
    @('coupons','coupon_user'),@('bookings','coupon_user'),@('activities','activity_associations')
)
$eri = 200
foreach ($edge in $erdEdges) {
    $from = $edge[0]; $to = $edge[1]
    if ($eids.ContainsKey($from) -and $eids.ContainsKey($to)) {
        $lbl = if ($edge.Length -gt 2) { $edge[2] } else { 'FK' }
        $pErd += (Edge "eri$eri" '1' $eids[$from] $eids[$to] $lbl)
        $eri++
    }
}
$pErd += PageEnd

# --- Page 3 Services ---
$p2 = PageStart "p2" "٣ - الخدمات" 1300 750
$svcs = @(
    @{n='ActivityService';d='إدارة الأنشطة + أقسام ثنائية اللغة';u='Activity'},
    @{n='DestinationService';d='إدارة الوجهات';u='Destination'},
    @{n='HotelService';d='إدارة الفنادق';u='Hotel'},
    @{n='WeatherService';d='جلب/تحديث الطقس من API';u='Destination, Weather'},
    @{n='DashboardService';d='إحصائيات لوحة المدير';u='Destination, Activity, Booking, User, Review, Contact'},
    @{n='UserService';d='إدارة المستخدمين';u='User'},
    @{n='ProviderEarningsService';d='حساب أرباح المزود';u='User, Booking'},
    @{n='ActivityAssociationService';d='بناء توصيات الارتباط';u='Booking, ActivityAssociation'},
    @{n='MissingEnglishFieldsService';d='تدقيق الحقول الإنجليزية الناقصة';u='n/a'}
)
$xi = 40; $yi = 50; $si = 20
foreach ($s in $svcs) {
    $p2 += (Box "s$si" "1" $xi $yi 280 90 $s.n @($s.d, "يعتمد على: $($s.u)") '#d5e8d4' '#82b366')
    $xi += 280
    if ($xi -gt 900) { $xi = 40; $yi += 110 }
    $si++
}
$p2 += (Box 's99' '1' 40 280 500 70 'المهام والأوامر' @('UpdateWeatherJob (طابور)','BuildActivityAssociations (أمر artisan)','CheckImages (أمر artisan)') '#e1d5e7' '#9673a6')
$p2 += (Box 's98' '1' 560 280 380 90 'السمات (Traits)' @('HasLocalizedAttributes -> Activity, Destination, Hotel, TravelBasic','UploadImageTrait -> أدوات رفع الصور') '#fff2cc' '#d6b656')
$p2 += (Box 's97' '1' 560 390 200 70 'سياسة الحجز BookingPolicy' @('تفويض الوصول لعمليات الحجز') '#f5f5f5' '#666666')
$p2 += PageEnd

# --- Page 4 Controllers ---
$p3 = PageStart "p3" "٤ - المتحكمات" 1400 950
$ctrl = @{
    'Admin (App\Http\Controllers\Admin)' = @(
        'AuthController','DashboardController','DestinationController','ActivityController',
        'HotelController','BookingController','UserController','ContentProviderController',
        'CouponController','ReviewController','CommentController','ContactController',
        'GalleryController','BadgeController','SettingController','TravelBasicController',
        'TravelBasicCategoryController','TravelPageController','RecommendationInsightsController'
    )
    'User / Website' = @(
        'AuthController','DestinationController','ActivityController','HotelController',
        'BookingController','ReviewController','CommentController','FavoriteController',
        'RecommendationController','StatisticsController'
    )
    'Provider' = @('DashboardController','ApplicationStatusController','ActivityGalleryController')
    'Public / Root' = @(
        'HomeController','EventController','ContactController','WeatherController',
        'TravelBasicsController','TravelPageController','InteractiveMapController',
        'ProvinceController','NotificationController','ProviderStorefrontController'
    )
}
$cx = 40; $cy = 50; $ci = 30
foreach ($pkg in $ctrl.Keys) {
    $items = $ctrl[$pkg]
    $h = 40 + ($items.Count * 20)
    $p3 += (Box "c$ci" "1" $cx $cy 520 $h $pkg ($items | ForEach-Object { "- $_" }) '#dae8fc' '#6c8ebf')
    $cy += $h + 25
    if ($cy -gt 450) { $cy = 50; $cx = 600 }
    $ci++
}
$p3 += (Box 'cbase' '1' 600 50 280 60 'Controller أساسي (مجرد)' @('extends Illuminate\Routing\Controller','كل متحكمات HTTP ترث App\Http\Controllers\Controller') '#fff2cc' '#d6b656')
$p3 += (Box 'cnote' '1' 40 820 900 50 'حقن الاعتمادية (DI)' @('Admin DestinationController -> DestinationService','Admin ActivityController -> ActivityService','Admin HotelController -> HotelService') '#d5e8d4' '#82b366')
$p3 += PageEnd

# --- Page 5 Notifications ---
$p4 = PageStart "p4" "٥ - الإشعارات" 1000 550
$notifs = @(
    'BookingCreated','BookingConfirmed','BookingCancelled',
    'ProviderBookingCreated','ContentProviderApplicationReviewed',
    'NewContactMessage','BadgeEarned'
)
$ny = 60
foreach ($n in $notifs) {
    $p4 += (Box "n$n" "1" 40 $ny 420 60 $n @('extends Notification','قنوات: mail / database') '#ffe6cc' '#d79b00')
    $ny += 65
}
$p4 += PageEnd

# --- Page 6 Infra ---
$p5 = PageStart "p5" "٦ - البنية التحتية" 1300 820
$p5 += (Box 'i1' '1' 40 50 360 290 'Middleware HTTP' @(
    'Authenticate',
    'RedirectIfAuthenticated',
    'EnsureApprovedContentProvider',
    'IsAdmin',
    'SetLocale',
    'VerifyCsrfToken',
    'EncryptCookies',
    'TrimStrings',
    'ValidateSignature',
    'TrustHosts',
    'TrustProxies',
    'PreventRequestsDuringMaintenance'
) '#f5f5f5' '#666666')
$p5 += (Box 'i2' '1' 440 50 360 170 'طلبات التحقق Form Requests' @(
    'ActivityRequest',
    'DestinationRequest',
    'HotelRequest',
    'ReviewRequest',
    'CommentRequest'
) '#e1d5e7' '#9673a6')
$p5 += (Box 'i3' '1' 840 50 360 110 'السياسات Policies' @(
    'BookingPolicy (authorize)'
) '#fff2cc' '#d6b656')
$p5 += (Box 'i4' '1' 440 260 360 110 'أوامر Artisan' @(
    'BuildActivityAssociations',
    'CheckImages'
) '#d5e8d4' '#82b366')
$p5 += (Box 'i5' '1' 840 200 360 80 'مهام الطابور Jobs' @(
    'UpdateWeatherJob'
) '#d5e8d4' '#82b366')
$p5 += (Box 'i6' '1' 40 370 1160 110 'ملاحظات' @(
    'تكمل صفحات النماذج والخدمات والمتحكمات.',
    'الإشعارات في الصفحة ٥، السمات في صفحة الخدمات.',
    'علاقات Eloquent هي المرجع لطبقة المجال.'
) '#dae8fc' '#6c8ebf')
$p5 += PageEnd

# --- Page 7 Requirements ---
$p6 = PageStart "p6" "٧ - المتطلبات (وظيفية/غير وظيفية)" 1500 950
$p6 += (Box 'r0' '1' 40 20 1320 60 'مخطط المتطلبات - دليل السائح' @(
    'الجهات الفاعلة + المتطلبات الوظيفية + المتطلبات غير الوظيفية',
    'مستخلص من المسارات والمتحكمات والـ middleware وطبقة الخدمات'
) '#dae8fc' '#6c8ebf')

# Actors
$p6 += (Box 'r1' '1' 40 110 220 70 'الفاعل: زائر' @(
    'تصفح الوجهات والأنشطة والفنادق',
    'عرض أساسيات السفر والخريطة والفعاليات'
) '#fff2cc' '#d6b656')
$p6 += (Box 'r2' '1' 40 200 220 70 'الفاعل: مستخدم' @(
    'سائح مسجل دخول',
    'الحجوزات والتقييمات والتعليقات والمفضلة'
) '#fff2cc' '#d6b656')
$p6 += (Box 'r3' '1' 40 290 220 70 'الفاعل: مزود محتوى' @(
    'مزود محتوى معتمد',
    'إدارة الأنشطة والمعرض والحجوزات'
) '#fff2cc' '#d6b656')
$p6 += (Box 'r4' '1' 40 380 220 70 'الفاعل: مدير' @(
    'الإشراف والتحكم بالمنصة'
) '#fff2cc' '#d6b656')

$p6 += (Box 'rHdrFr' '1' 280 95 1180 35 'المتطلبات الوظيفية (Functional Requirements)' @() '#d5e8d4' '#82b366')
# Functional requirements (يمين الفاعلين لتجنب التداخل)
$p6 += (Box 'r10' '1' 280 140 320 115 'FR1 - المصادقة والحسابات' @(
    'تسجيل/دخول + تسجيل اجتماعي (Socialite)',
    'تسجيل مزود محتوى ومتابعة حالة الطلب',
    'صلاحيات حسب الدور: user / provider / admin'
) '#d5e8d4' '#82b366')
$p6 += (Box 'r11' '1' 620 140 320 115 'FR2 - استكشاف المحتوى' @(
    'عرض الوجهات والأنشطة والفنادق',
    'تقويم الفعاليات والخريطة التفاعلية',
    'طقس الوجهة + مقارنة الوجهات + توصيات'
) '#d5e8d4' '#82b366')
$p6 += (Box 'r12' '1' 960 140 320 115 'FR3 - الحجوزات' @(
    'إنشاء حجز وعرض السجل',
    'إلغاء الحجز والتحقق من الكوبون',
    'حالة الدفع وأرباح المزود'
) '#d5e8d4' '#82b366')
$p6 += (Box 'r13' '1' 280 270 320 115 'FR4 - التفاعل' @(
    'تقييمات وتعليقات على الأنشطة',
    'المفضلة (toggle + قائمة)',
    'مركز الإشعارات'
) '#d5e8d4' '#82b366')
$p6 += (Box 'r14' '1' 620 270 320 115 'FR5 - مزود المحتوى' @(
    'لوحة تحكم المزود وتحديث الحساب',
    'CRUD أنشطة + معرض صور',
    'تصدير CSV للحجوزات والأرباح'
) '#d5e8d4' '#82b366')
$p6 += (Box 'r15' '1' 960 270 320 115 'FR6 - الإدارة' @(
    'إدارة وجهات/أنشطة/فنادق/مستخدمين',
    'إشراف على تقييمات/تعليقات/حجوزات',
    'كوبونات + رسائل تواصل + اعتماد مزودين'
) '#d5e8d4' '#82b366')
$p6 += (Box 'r16' '1' 280 400 320 115 'FR7 - التوصيات' @(
    'توصيات الأنشطة المرتبطة',
    'تحليلات التوصيات للمدير'
) '#d5e8d4' '#82b366')
$p6 += (Box 'r17' '1' 620 400 320 115 'FR8 - اللغات' @(
    'حقول عربي/إنجليزي للمحتوى',
    'تبديل اللغة وسمة HasLocalizedAttributes'
) '#d5e8d4' '#82b366')
$p6 += (Box 'r18' '1' 960 400 320 100 'FR9 - التواصل' @(
    'نموذج تواصل من الموقع',
    'معالجة الرسائل من لوحة المدير'
) '#d5e8d4' '#82b366')
$p6 += (Box 'r19' '1' 280 530 320 100 'FR10 - واجهة الشركاء' @(
    'قائمة الشركاء وصفحة متجر المزود (storefront)'
) '#d5e8d4' '#82b366')

$p6 += (Box 'rHdrNfr' '1' 280 650 1000 35 'المتطلبات غير الوظيفية (Non-Functional)' @() '#f8cecc' '#b85450')
# Non-functional requirements
$p6 += (Box 'r20' '1' 280 700 400 130 'NFR1 - الأمان' @(
    'طبقة حماية المصادقة و CSRF',
    'حراس الصلاحيات للمدير/المزود وسياسات التفويض',
    'روابط موقعة والتحقق من الطلبات'
) '#f8cecc' '#b85450')
$p6 += (Box 'r21' '1' 700 700 400 130 'NFR2 - الأداء والتوسع' @(
    'استعلامات مفهرسة للارتباطات والطقس',
    'مهام طابور لتحديث بيانات الطقس',
    'صفحات فعالة في مسارات المستخدم والمدير'
) '#f8cecc' '#b85450')
$p6 += (Box 'r22' '1' 1120 700 400 130 'NFR3 - الصيانة' @(
    'طبقة خدمات منفصلة لمنطق الأعمال',
    'FormRequests للتحقق من المدخلات',
    'متحكمات منظمة حسب الدور والمجال'
) '#f8cecc' '#b85450')

# Traceability links
$p6 += (Edge 're1' '1' 'r1' 'r11' 'يستخدم')
$p6 += (Edge 're2' '1' 'r1' 'r18' 'يستخدم')
$p6 += (Edge 're3' '1' 'r2' 'r10' 'يستخدم')
$p6 += (Edge 're4' '1' 'r2' 'r12' 'يستخدم')
$p6 += (Edge 're5' '1' 'r2' 'r13' 'يستخدم')
$p6 += (Edge 're6' '1' 'r3' 'r14' 'يستخدم')
$p6 += (Edge 're7' '1' 'r4' 'r15' 'يدير')
$p6 += (Edge 're8' '1' 'r4' 'r16' 'يدير')
$p6 += PageEnd

# --- Page 8 Use Case Diagram ---
$p7 = PageStart "p7" "٨ - حالات الاستخدام (Use Case)" 2000 1350
$p7 += @"
        <mxCell id="uTitle" value="مخطط حالات الاستخدام الموسّع - منصة دليل السائح" style="text;html=1;strokeColor=none;fillColor=none;align=center;verticalAlign=middle;fontSize=18;fontStyle=1;" vertex="1" parent="1">
          <mxGeometry x="520" y="10" width="860" height="40" as="geometry"/>
        </mxCell>
        <mxCell id="uBoundary" value="حدود النظام: نظام دليل السائح" style="rounded=0;whiteSpace=wrap;html=1;fillColor=#ffffff;strokeColor=#666666;strokeWidth=2;align=left;verticalAlign=top;spacingLeft=10;spacingTop=8;fontStyle=1;" vertex="1" parent="1">
          <mxGeometry x="300" y="70" width="1540" height="1180" as="geometry"/>
        </mxCell>

        <mxCell id="aGuest" value="زائر" style="shape=umlActor;verticalLabelPosition=bottom;verticalAlign=top;html=1;outlineConnect=0;" vertex="1" parent="1">
          <mxGeometry x="80" y="150" width="60" height="120" as="geometry"/>
        </mxCell>
        <mxCell id="aUser" value="مستخدم" style="shape=umlActor;verticalLabelPosition=bottom;verticalAlign=top;html=1;outlineConnect=0;" vertex="1" parent="1">
          <mxGeometry x="80" y="380" width="60" height="120" as="geometry"/>
        </mxCell>
        <mxCell id="aProvider" value="مزود محتوى" style="shape=umlActor;verticalLabelPosition=bottom;verticalAlign=top;html=1;outlineConnect=0;" vertex="1" parent="1">
          <mxGeometry x="80" y="630" width="60" height="120" as="geometry"/>
        </mxCell>
        <mxCell id="aAdmin" value="مدير" style="shape=umlActor;verticalLabelPosition=bottom;verticalAlign=top;html=1;outlineConnect=0;" vertex="1" parent="1">
          <mxGeometry x="80" y="930" width="60" height="120" as="geometry"/>
        </mxCell>
        <mxCell id="aWeatherApi" value="خدمة الطقس الخارجية" style="shape=umlActor;verticalLabelPosition=bottom;verticalAlign=top;html=1;outlineConnect=0;" vertex="1" parent="1">
          <mxGeometry x="1760" y="180" width="80" height="130" as="geometry"/>
        </mxCell>
        <mxCell id="aSocial" value="مزود تسجيل اجتماعي" style="shape=umlActor;verticalLabelPosition=bottom;verticalAlign=top;html=1;outlineConnect=0;" vertex="1" parent="1">
          <mxGeometry x="1760" y="440" width="80" height="130" as="geometry"/>
        </mxCell>

        <mxCell id="uc1" value="تصفح الوجهات" style="ellipse;whiteSpace=wrap;html=1;fillColor=#dae8fc;strokeColor=#6c8ebf;" vertex="1" parent="1"><mxGeometry x="360" y="120" width="210" height="60" as="geometry"/></mxCell>
        <mxCell id="uc2" value="تصفح الأنشطة" style="ellipse;whiteSpace=wrap;html=1;fillColor=#dae8fc;strokeColor=#6c8ebf;" vertex="1" parent="1"><mxGeometry x="600" y="120" width="210" height="60" as="geometry"/></mxCell>
        <mxCell id="uc3" value="عرض تفاصيل وجهة/نشاط" style="ellipse;whiteSpace=wrap;html=1;fillColor=#dae8fc;strokeColor=#6c8ebf;" vertex="1" parent="1"><mxGeometry x="840" y="120" width="250" height="60" as="geometry"/></mxCell>
        <mxCell id="uc4" value="عرض طقس الوجهات" style="ellipse;whiteSpace=wrap;html=1;fillColor=#dae8fc;strokeColor=#6c8ebf;" vertex="1" parent="1"><mxGeometry x="1120" y="120" width="220" height="60" as="geometry"/></mxCell>
        <mxCell id="uc5" value="عرض الفعاليات والتقويم" style="ellipse;whiteSpace=wrap;html=1;fillColor=#dae8fc;strokeColor=#6c8ebf;" vertex="1" parent="1"><mxGeometry x="360" y="200" width="250" height="60" as="geometry"/></mxCell>
        <mxCell id="uc6" value="استعراض الخريطة التفاعلية" style="ellipse;whiteSpace=wrap;html=1;fillColor=#dae8fc;strokeColor=#6c8ebf;" vertex="1" parent="1"><mxGeometry x="640" y="200" width="260" height="60" as="geometry"/></mxCell>
        <mxCell id="uc7" value="عرض أساسيات السفر" style="ellipse;whiteSpace=wrap;html=1;fillColor=#dae8fc;strokeColor=#6c8ebf;" vertex="1" parent="1"><mxGeometry x="930" y="200" width="210" height="60" as="geometry"/></mxCell>

        <mxCell id="uc8" value="التسجيل/تسجيل الدخول" style="ellipse;whiteSpace=wrap;html=1;fillColor=#d5e8d4;strokeColor=#82b366;" vertex="1" parent="1"><mxGeometry x="360" y="320" width="230" height="60" as="geometry"/></mxCell>
        <mxCell id="uc9" value="تسجيل دخول اجتماعي" style="ellipse;whiteSpace=wrap;html=1;fillColor=#fff2cc;strokeColor=#d6b656;" vertex="1" parent="1"><mxGeometry x="620" y="320" width="210" height="60" as="geometry"/></mxCell>
        <mxCell id="uc10" value="إدارة الملف الشخصي" style="ellipse;whiteSpace=wrap;html=1;fillColor=#d5e8d4;strokeColor=#82b366;" vertex="1" parent="1"><mxGeometry x="860" y="320" width="210" height="60" as="geometry"/></mxCell>
        <mxCell id="uc11" value="إضافة للمفضلة/إزالتها" style="ellipse;whiteSpace=wrap;html=1;fillColor=#d5e8d4;strokeColor=#82b366;" vertex="1" parent="1"><mxGeometry x="1100" y="320" width="240" height="60" as="geometry"/></mxCell>
        <mxCell id="uc12" value="عرض صفحة المفضلة" style="ellipse;whiteSpace=wrap;html=1;fillColor=#d5e8d4;strokeColor=#82b366;" vertex="1" parent="1"><mxGeometry x="1360" y="320" width="210" height="60" as="geometry"/></mxCell>

        <mxCell id="uc13" value="إنشاء حجز نشاط" style="ellipse;whiteSpace=wrap;html=1;fillColor=#d5e8d4;strokeColor=#82b366;" vertex="1" parent="1"><mxGeometry x="360" y="410" width="210" height="60" as="geometry"/></mxCell>
        <mxCell id="uc14" value="التحقق من الكوبون" style="ellipse;whiteSpace=wrap;html=1;fillColor=#fff2cc;strokeColor=#d6b656;" vertex="1" parent="1"><mxGeometry x="600" y="410" width="210" height="60" as="geometry"/></mxCell>
        <mxCell id="uc15" value="عرض الحجوزات" style="ellipse;whiteSpace=wrap;html=1;fillColor=#d5e8d4;strokeColor=#82b366;" vertex="1" parent="1"><mxGeometry x="840" y="410" width="190" height="60" as="geometry"/></mxCell>
        <mxCell id="uc16" value="إلغاء الحجز" style="ellipse;whiteSpace=wrap;html=1;fillColor=#d5e8d4;strokeColor=#82b366;" vertex="1" parent="1"><mxGeometry x="1060" y="410" width="180" height="60" as="geometry"/></mxCell>
        <mxCell id="uc17" value="إدارة الإشعارات" style="ellipse;whiteSpace=wrap;html=1;fillColor=#d5e8d4;strokeColor=#82b366;" vertex="1" parent="1"><mxGeometry x="1260" y="410" width="200" height="60" as="geometry"/></mxCell>
        <mxCell id="uc18" value="إضافة تقييم/تعليق" style="ellipse;whiteSpace=wrap;html=1;fillColor=#d5e8d4;strokeColor=#82b366;" vertex="1" parent="1"><mxGeometry x="1480" y="410" width="200" height="60" as="geometry"/></mxCell>

        <mxCell id="uc19" value="تقديم طلب مزود محتوى" style="ellipse;whiteSpace=wrap;html=1;fillColor=#ffe6cc;strokeColor=#d79b00;" vertex="1" parent="1"><mxGeometry x="360" y="580" width="260" height="60" as="geometry"/></mxCell>
        <mxCell id="uc20" value="متابعة حالة الطلب" style="ellipse;whiteSpace=wrap;html=1;fillColor=#ffe6cc;strokeColor=#d79b00;" vertex="1" parent="1"><mxGeometry x="650" y="580" width="220" height="60" as="geometry"/></mxCell>
        <mxCell id="uc21" value="لوحة تحكم المزود" style="ellipse;whiteSpace=wrap;html=1;fillColor=#ffe6cc;strokeColor=#d79b00;" vertex="1" parent="1"><mxGeometry x="900" y="580" width="210" height="60" as="geometry"/></mxCell>
        <mxCell id="uc22" value="إدارة الأنشطة (CRUD)" style="ellipse;whiteSpace=wrap;html=1;fillColor=#ffe6cc;strokeColor=#d79b00;" vertex="1" parent="1"><mxGeometry x="1140" y="580" width="220" height="60" as="geometry"/></mxCell>
        <mxCell id="uc23" value="إدارة معرض الصور" style="ellipse;whiteSpace=wrap;html=1;fillColor=#ffe6cc;strokeColor=#d79b00;" vertex="1" parent="1"><mxGeometry x="1390" y="580" width="210" height="60" as="geometry"/></mxCell>
        <mxCell id="uc24" value="تصدير الحجوزات/الأرباح CSV" style="ellipse;whiteSpace=wrap;html=1;fillColor=#ffe6cc;strokeColor=#d79b00;" vertex="1" parent="1"><mxGeometry x="360" y="660" width="280" height="60" as="geometry"/></mxCell>
        <mxCell id="uc25" value="إدارة حساب المزود" style="ellipse;whiteSpace=wrap;html=1;fillColor=#ffe6cc;strokeColor=#d79b00;" vertex="1" parent="1"><mxGeometry x="670" y="660" width="220" height="60" as="geometry"/></mxCell>

        <mxCell id="uc26" value="تسجيل دخول المدير" style="ellipse;whiteSpace=wrap;html=1;fillColor=#f8cecc;strokeColor=#b85450;" vertex="1" parent="1"><mxGeometry x="360" y="860" width="210" height="60" as="geometry"/></mxCell>
        <mxCell id="uc27" value="مراجعة طلبات مزودي المحتوى" style="ellipse;whiteSpace=wrap;html=1;fillColor=#f8cecc;strokeColor=#b85450;" vertex="1" parent="1"><mxGeometry x="600" y="860" width="270" height="60" as="geometry"/></mxCell>
        <mxCell id="uc28" value="إدارة الوجهات/الأنشطة/الفنادق" style="ellipse;whiteSpace=wrap;html=1;fillColor=#f8cecc;strokeColor=#b85450;" vertex="1" parent="1"><mxGeometry x="900" y="860" width="320" height="60" as="geometry"/></mxCell>
        <mxCell id="uc29" value="إدارة الحجوزات/المدفوعات" style="ellipse;whiteSpace=wrap;html=1;fillColor=#f8cecc;strokeColor=#b85450;" vertex="1" parent="1"><mxGeometry x="1250" y="860" width="270" height="60" as="geometry"/></mxCell>
        <mxCell id="uc30" value="إدارة التعليقات/التقييمات" style="ellipse;whiteSpace=wrap;html=1;fillColor=#f8cecc;strokeColor=#b85450;" vertex="1" parent="1"><mxGeometry x="1540" y="860" width="250" height="60" as="geometry"/></mxCell>

        <mxCell id="uc31" value="إدارة الكوبونات" style="ellipse;whiteSpace=wrap;html=1;fillColor=#f8cecc;strokeColor=#b85450;" vertex="1" parent="1"><mxGeometry x="360" y="940" width="200" height="60" as="geometry"/></mxCell>
        <mxCell id="uc32" value="إدارة المستخدمين" style="ellipse;whiteSpace=wrap;html=1;fillColor=#f8cecc;strokeColor=#b85450;" vertex="1" parent="1"><mxGeometry x="590" y="940" width="200" height="60" as="geometry"/></mxCell>
        <mxCell id="uc33" value="إدارة رسائل التواصل" style="ellipse;whiteSpace=wrap;html=1;fillColor=#f8cecc;strokeColor=#b85450;" vertex="1" parent="1"><mxGeometry x="820" y="940" width="230" height="60" as="geometry"/></mxCell>
        <mxCell id="uc34" value="إدارة أساسيات السفر" style="ellipse;whiteSpace=wrap;html=1;fillColor=#f8cecc;strokeColor=#b85450;" vertex="1" parent="1"><mxGeometry x="1080" y="940" width="230" height="60" as="geometry"/></mxCell>
        <mxCell id="uc35" value="عرض تحليلات التوصيات" style="ellipse;whiteSpace=wrap;html=1;fillColor=#f8cecc;strokeColor=#b85450;" vertex="1" parent="1"><mxGeometry x="1340" y="940" width="240" height="60" as="geometry"/></mxCell>
"@
$p7 += (Edge 'ue1' '1' 'aGuest' 'uc1' '')
$p7 += (Edge 'ue2' '1' 'aGuest' 'uc2' '')
$p7 += (Edge 'ue3' '1' 'aGuest' 'uc3' '')
$p7 += (Edge 'ue4' '1' 'aGuest' 'uc4' '')
$p7 += (Edge 'ue5' '1' 'aGuest' 'uc5' '')
$p7 += (Edge 'ue6' '1' 'aGuest' 'uc6' '')
$p7 += (Edge 'ue7' '1' 'aGuest' 'uc7' '')
$p7 += (Edge 'ue8' '1' 'aUser' 'uc8' '')
$p7 += (Edge 'ue9' '1' 'aUser' 'uc10' '')
$p7 += (Edge 'ue10' '1' 'aUser' 'uc11' '')
$p7 += (Edge 'ue11' '1' 'aUser' 'uc12' '')
$p7 += (Edge 'ue12' '1' 'aUser' 'uc13' '')
$p7 += (Edge 'ue13' '1' 'aUser' 'uc15' '')
$p7 += (Edge 'ue14' '1' 'aUser' 'uc16' '')
$p7 += (Edge 'ue15' '1' 'aUser' 'uc17' '')
$p7 += (Edge 'ue16' '1' 'aUser' 'uc18' '')
$p7 += (Edge 'ue17' '1' 'aProvider' 'uc19' '')
$p7 += (Edge 'ue18' '1' 'aProvider' 'uc20' '')
$p7 += (Edge 'ue19' '1' 'aProvider' 'uc21' '')
$p7 += (Edge 'ue20' '1' 'aProvider' 'uc22' '')
$p7 += (Edge 'ue21' '1' 'aProvider' 'uc23' '')
$p7 += (Edge 'ue22' '1' 'aProvider' 'uc24' '')
$p7 += (Edge 'ue23' '1' 'aProvider' 'uc25' '')
$p7 += (Edge 'ue24' '1' 'aAdmin' 'uc26' '')
$p7 += (Edge 'ue25' '1' 'aAdmin' 'uc27' '')
$p7 += (Edge 'ue26' '1' 'aAdmin' 'uc28' '')
$p7 += (Edge 'ue27' '1' 'aAdmin' 'uc29' '')
$p7 += (Edge 'ue28' '1' 'aAdmin' 'uc30' '')
$p7 += (Edge 'ue29' '1' 'aAdmin' 'uc31' '')
$p7 += (Edge 'ue30' '1' 'aAdmin' 'uc32' '')
$p7 += (Edge 'ue31' '1' 'aAdmin' 'uc33' '')
$p7 += (Edge 'ue32' '1' 'aAdmin' 'uc34' '')
$p7 += (Edge 'ue33' '1' 'aAdmin' 'uc35' '')
$p7 += (Edge 'ue34' '1' 'aWeatherApi' 'uc4' 'API')
$p7 += (Edge 'ue35' '1' 'aSocial' 'uc9' 'OAuth')
$p7 += (Edge 'ue36' '1' 'uc13' 'uc14' '<<include>>', $true)
$p7 += (Edge 'ue37' '1' 'uc8' 'uc9' '<<extend>>', $true)
$p7 += (Edge 'ue38' '1' 'uc22' 'uc23' '<<include>>', $true)
$p7 += (Edge 'ue39' '1' 'uc27' 'uc29' '<<extend>>', $true)
$p7 += PageEnd

$xml = @"
<?xml version="1.0" encoding="UTF-8"?>
<mxfile host="app.diagrams.net" modified="2026-06-01T12:00:00.000Z" agent="tourist-guide" version="22.1.0" type="device" compressed="false" pages="10">
$pArch
$p1
$pErd
$p2
$p3
$p4
$p5
$p6
$p7
$p0
</mxfile>
"@

$utf8 = New-Object System.Text.UTF8Encoding $true
[System.IO.File]::WriteAllText($out, $xml, $utf8)
Copy-Item $out $outLegacy -Force
Copy-Item $out $outRoot -Force
Write-Host "OK: $out"
Write-Host "Legacy copies: $outLegacy , $outRoot"


