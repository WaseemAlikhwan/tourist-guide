<?php

namespace Database\Seeders;

use App\Models\TravelBasic;
use Illuminate\Database\Seeder;

class TravelBasicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $titleTranslations = [
            'جواز السفر والتأشيرات' => 'Passport and Visas',
            'العملة والبنوك' => 'Currency and Banking',
            'الاتصالات والإنترنت' => 'Communications and Internet',
            'المواصلات' => 'Transportation',
            'الثقافة والعادات' => 'Culture and Traditions',
            'الطعام والمطاعم' => 'Food and Restaurants',
            'الأمان والصحة' => 'Safety and Health',
            'المناخ والملابس' => 'Climate and Clothing',
            'اللغة والتواصل' => 'Language and Communication',
            'التسوق والهدايا التذكارية' => 'Shopping and Souvenirs',
        ];

        $itemTranslations = [
            'يجب أن يكون جواز السفر صالحاً لمدة 6 أشهر على الأقل من تاريخ الدخول' => 'Your passport must be valid for at least 6 months from the date of entry.',
            'بعض الجنسيات تحتاج إلى تأشيرة دخول مسبقة' => 'Some nationalities require a visa in advance.',
            'تأشيرة سياحية صالحة لمدة 30-90 يوماً حسب الجنسية' => 'Tourist visas are typically valid for 30-90 days depending on nationality.',
            'يمكن الحصول على التأشيرة عند الوصول للمطار للبعض' => 'Some travelers can obtain a visa on arrival at the airport.',
            'يُنصح بالتحقق من متطلبات التأشيرة قبل السفر' => 'It is recommended to check visa requirements before traveling.',
            'العملة الرسمية هي الليرة السورية (SYP)' => 'The official currency is the Syrian Pound (SYP).',
            'يمكن صرف العملات الأجنبية في البنوك ومكاتب الصرافة' => 'Foreign currencies can be exchanged at banks and exchange offices.',
            'يُنصح بحمل العملة النقدية حيث أن البطاقات الائتمانية محدودة الاستخدام' => 'Carrying cash is recommended, as credit card usage is limited.',
            'أسعار الصرف متغيرة، يُنصح بالتحقق من الأسعار الحالية' => 'Exchange rates fluctuate, so check the latest rates.',
            'البطاقات الائتمانية الدولية محدودة القبول' => 'International credit cards have limited acceptance.',
            'هناك ثلاثة شركات اتصالات رئيسية: سيرياتل، MTN، وSyriatel' => 'There are three major telecom operators: Syriatel, MTN, and Syriatel.',
            'يمكن شراء شريحة SIM من أي من مكاتب الشركات' => 'You can purchase a SIM card from company offices.',
            'الإنترنت متاح في معظم الفنادق والمقاهي' => 'Internet is available in most hotels and cafes.',
            'الإنترنت قد يكون بطيئاً في بعض المناطق' => 'Internet may be slow in some areas.',
            'يُنصح بتنزيل الخرائط قبل السفر' => 'Download maps before traveling.',
            'التاكسي متاح في جميع المدن الرئيسية' => 'Taxis are available in all major cities.',
            'الحافلات العامة هي وسيلة نقل اقتصادية' => 'Public buses are an economical means of transport.',
            'يمكن استئجار سيارة من شركات التأجير الدولية' => 'Cars can be rented from international rental companies.',
            'يُنصح بالاتفاق على السعر قبل ركوب التاكسي' => 'Agree on the fare before getting into a taxi.',
            'تطبيقات النقل محدودة أو غير متاحة' => 'Ride-hailing apps are limited or unavailable.',
            'المطبخ السوري غني ومتنوع ومن أشهره في المنطقة' => 'Syrian cuisine is rich, diverse, and among the most famous in the region.',
            'الوجبات الرئيسية عادة ما تكون كبيرة ومشبعة' => 'Main meals are usually large and filling.',
            'يُنصح بتجربة المأكولات المحلية في المطاعم التقليدية' => 'Try local dishes in traditional restaurants.',
            'الشاي والقهوة جزء من الثقافة المحلية' => 'Tea and coffee are part of local culture.',
            'المعاملات النقدية شائعة في معظم المطاعم' => 'Cash payments are common in most restaurants.',
            'المناخ معتدل بشكل عام، لكن يختلف حسب المنطقة' => 'The climate is generally mild but varies by region.',
            'الصيف حار وجاف، الشتاء بارد وممطر' => 'Summer is hot and dry, while winter is cold and rainy.',
            'يُنصح بحمل ملابس مناسبة للموسم' => 'Bring clothes suitable for the season.',
            'في المدن الساحلية، الجو معتدل معظم السنة' => 'In coastal cities, weather is mild most of the year.',
            'في المناطق الجبلية، الجو بارد في الشتاء' => 'In mountainous areas, winters are cold.',
            'المساومة على الأسعار شائعة في الأسواق التقليدية' => 'Bargaining is common in traditional markets.',
            'المشغولات اليدوية والتحف من أفضل الهدايا التذكارية' => 'Handicrafts and antiques are among the best souvenirs.',
            'الأقمشة الحريرية والسجاد التقليدي شهيران' => 'Silk fabrics and traditional carpets are popular.',
            'الصابون الحلبي التقليدي من أشهر الهدايا' => 'Traditional Aleppo soap is a famous gift item.',
            'المشروبات والمأكولات المحلية خيارات جيدة للهدايا' => 'Local drinks and food products are good gift options.',
        ];

        $contentTranslations = [
            'سوريا بلد متنوع ثقافياً ودينياً. يُنصح باحترام العادات والتقاليد المحلية. الملابس المحتشمة مُفضلة خاصة عند زيارة المساجد والكنائس. المصافحة باليد هي التحية المعتادة. عند الدخول إلى المنازل، يُفضل خلع الأحذية.' => 'Syria is culturally and religiously diverse. Respect for local customs and traditions is recommended. Modest clothing is preferred, especially when visiting mosques and churches. Handshakes are the usual greeting. It is polite to remove shoes when entering homes.',
            'يُنصح بالحصول على تأمين سفر شامل قبل السفر. تحقق من التطعيمات المطلوبة قبل السفر. احمل نسخة من جواز سفرك وأوراقك المهمة. تجنب المناطق غير الآمنة واتبع إرشادات السلطات المحلية.' => 'It is recommended to obtain comprehensive travel insurance before your trip. Check required vaccinations in advance. Carry copies of your passport and important documents. Avoid unsafe areas and follow local authority guidance.',
            'اللغة العربية هي اللغة الرسمية، لكن اللغة الإنجليزية تُستخدم في الفنادق والمطاعم السياحية. الفرنسية مفهومة في بعض المناطق. تعلم بعض الكلمات العربية الأساسية سيساعدك في التواصل ويُظهر احترامك للثقافة المحلية.' => 'Arabic is the official language, but English is used in hotels and tourist restaurants. French is understood in some areas. Learning a few basic Arabic phrases helps communication and shows respect for local culture.',
        ];

        $sectionTitleTranslations = [
            'متطلبات التأشيرة حسب الجنسية' => 'Visa Requirements by Nationality',
            'نصائح الصرافة' => 'Currency Exchange Tips',
            'باقات الإنترنت والاتصالات' => 'Internet and Calling Plans',
            'استئجار السيارة' => 'Car Rental',
            'زيارة المواقع الدينية' => 'Visiting Religious Sites',
            'المعاملات الاجتماعية' => 'Social Etiquette',
            'أطباق لا تفوتها' => 'Must-Try Dishes',
            'الصحة والرعاية الطبية' => 'Health and Medical Care',
            'نصائح الأمان' => 'Safety Tips',
            'ما يجب إحضاره' => 'What to Pack',
            'كلمات عربية مفيدة' => 'Useful Arabic Phrases',
            'أفضل أماكن التسوق' => 'Best Shopping Spots',
        ];

        $sectionContentTranslations = [
            'تختلف متطلبات التأشيرة حسب جنسية المسافر. الجنسيات العربية عادة لا تحتاج تأشيرة أو يمكنها الحصول عليها عند الوصول. الجنسيات الأوروبية والأمريكية تحتاج عادة إلى تأشيرة مسبقة. يُنصح بالتحقق من السفارة السورية في بلدك قبل السفر.' => 'Visa requirements vary by traveler nationality. Arab nationalities often do not need a visa or can obtain one on arrival. European and American nationalities usually require a visa in advance. Check with the Syrian embassy in your country before travel.',
            'يُنصح بصرف العملة في مكاتب الصرافة الرسمية وليس في الشارع. احتفظ بإيصالات الصرافة. تجنب صرف كميات كبيرة دفعة واحدة، وصرف ما تحتاجه حسب الحاجة.' => 'Exchange money at official exchange offices rather than on the street. Keep exchange receipts. Avoid exchanging large amounts at once and convert only what you need.',
            'توفر شركات الاتصالات باقات متنوعة للاتصالات والإنترنت. الباقات الشهرية توفر عادة دقائق مجانية وبيانات إنترنت. يُنصح بشراء باقة مناسبة لفترة إقامتك.' => 'Telecom providers offer a variety of calling and internet packages. Monthly plans usually include free minutes and data. Choose a package that fits your stay duration.',
            'للاستئجار سيارة، تحتاج إلى رخصة قيادة دولية. يُنصح بالتأمين الشامل. القيادة في سوريا قد تكون صعبة في بعض المناطق، خاصة في المدن الكبيرة بسبب حركة المرور.' => 'To rent a car, you need an international driving license. Full insurance is recommended. Driving in Syria can be challenging in some areas, especially in large cities due to traffic.',
            'عند زيارة المساجد، يُطلب من النساء ارتداء الحجاب وتغطية الرأس والذراعين والساقين. عند زيارة الكنائس، يُفضل الملابس المحتشمة. يُمنع التصوير في بعض المواقع الدينية، يُنصح بطلب الإذن أولاً.' => 'When visiting mosques, women are expected to wear a headscarf and cover their head, arms, and legs. Modest clothing is also recommended for church visits. Photography is prohibited in some religious sites, so ask for permission first.',
            'الشعب السوري معروف بكرمه وحسن الضيافة. إذا دُعيت إلى منزل، من المهذب إحضار هدية صغيرة مثل الحلويات أو الزهور. من غير المعتاد رفض دعوة إلى الطعام أو القهوة.' => 'Syrians are known for generosity and hospitality. If invited to a home, it is polite to bring a small gift such as sweets or flowers. Declining offers of food or coffee is uncommon.',
            'من الأطباق التي يجب تجربتها: الكبابة الحلبية، الفتوش، المقلوبة، المحاشي، البرغل، والحمص. الحلويات السورية شهيرة مثل البقلاوة والكنافة. لا تنس تجربة العصير الطازج والآيس كريم التقليدي.' => 'Must-try dishes include Aleppo kebab, fattoush, maqluba, stuffed vegetables, bulgur, and hummus. Syrian desserts such as baklava and kunafa are famous. Do not miss fresh juices and traditional ice cream.',
            'يُنصح بشرب الماء المعبأ فقط. تجنب الطعام من الباعة المتجولين غير المعروفين. تأكد من وجود تأمين طبي شامل. المستشفيات الحكومية متاحة لكن قد تكون مزدحمة، المستشفيات الخاصة توفر رعاية أفضل لكنها أغلى.' => 'Drink only bottled water. Avoid food from unknown street vendors. Make sure you have comprehensive health insurance. Public hospitals are available but may be crowded, while private hospitals usually provide better but more expensive care.',
            'احتفظ بنسخة رقمية من أوراقك المهمة. تجنب المشي ليلاً في المناطق غير المعروفة. احتفظ برقم السفارة أو القنصلية لبلدك. اتبع إرشادات السلطات المحلية وأخبر شخصاً موثوقاً بخطة سفرك.' => 'Keep digital copies of your important documents. Avoid walking at night in unfamiliar areas. Save your embassy or consulate contact number. Follow local authority guidance and share your travel plan with someone you trust.',
            'في الصيف: ملابس قطنية خفيفة، قبعة، نظارات شمس، واقي شمس. في الشتاء: معطف دافئ، ملابس داخلية حرارية، وشاح وقبعة. على مدار السنة: أحذية مريحة للمشي، ملابس محتشمة للمواقع الدينية، وبطاريات احتياطية للكاميرا.' => 'In summer: light cotton clothes, a hat, sunglasses, and sunscreen. In winter: a warm coat, thermal layers, a scarf, and a hat. Year-round: comfortable walking shoes, modest clothing for religious sites, and spare camera batteries.',
            'مرحباً (hello)، شكراً (thank you)، لو سمحت (please)، مع السلامة (goodbye)، كم السعر؟ (how much?)، أين؟ (where?)، مرحباً بك (welcome)، صباح الخير (good morning)، مساء الخير (good evening).' => 'Marhaban (hello), Shukran (thank you), Law samaht (please), Maa al-salama (goodbye), Kam al-si\'r? (how much?), Ayna? (where?), Ahlan bik (welcome), Sabah al-khair (good morning), Masa al-khair (good evening).',
            'السوق المسقوف في حلب هو من أقدم الأسواق في العالم. سوق الحميدية في دمشق مشهور بالملابس والمشغولات اليدوية. في المدن الكبيرة، هناك مراكز تسوق حديثة. لا تنس زيارة محلات الصابون الحلبي والصناعات التقليدية.' => 'The covered souq in Aleppo is one of the oldest markets in the world. Al-Hamidiyah Souq in Damascus is famous for clothing and handicrafts. In major cities, modern shopping centers are available. Do not miss Aleppo soap shops and traditional craft stores.',
        ];

        $travelBasics = [
            [
                'title' => 'جواز السفر والتأشيرات',
                'icon' => 'fas fa-passport',
                'content_type' => 'list',
                'items' => [
                    'يجب أن يكون جواز السفر صالحاً لمدة 6 أشهر على الأقل من تاريخ الدخول',
                    'بعض الجنسيات تحتاج إلى تأشيرة دخول مسبقة',
                    'تأشيرة سياحية صالحة لمدة 30-90 يوماً حسب الجنسية',
                    'يمكن الحصول على التأشيرة عند الوصول للمطار للبعض',
                    'يُنصح بالتحقق من متطلبات التأشيرة قبل السفر',
                ],
                'custom_sections' => [
                    [
                        'title' => 'متطلبات التأشيرة حسب الجنسية',
                        'content' => 'تختلف متطلبات التأشيرة حسب جنسية المسافر. الجنسيات العربية عادة لا تحتاج تأشيرة أو يمكنها الحصول عليها عند الوصول. الجنسيات الأوروبية والأمريكية تحتاج عادة إلى تأشيرة مسبقة. يُنصح بالتحقق من السفارة السورية في بلدك قبل السفر.',
                    ],
                ],
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'العملة والبنوك',
                'icon' => 'fas fa-coins',
                'content_type' => 'list',
                'items' => [
                    'العملة الرسمية هي الليرة السورية (SYP)',
                    'يمكن صرف العملات الأجنبية في البنوك ومكاتب الصرافة',
                    'يُنصح بحمل العملة النقدية حيث أن البطاقات الائتمانية محدودة الاستخدام',
                    'أسعار الصرف متغيرة، يُنصح بالتحقق من الأسعار الحالية',
                    'البطاقات الائتمانية الدولية محدودة القبول',
                ],
                'custom_sections' => [
                    [
                        'title' => 'نصائح الصرافة',
                        'content' => 'يُنصح بصرف العملة في مكاتب الصرافة الرسمية وليس في الشارع. احتفظ بإيصالات الصرافة. تجنب صرف كميات كبيرة دفعة واحدة، وصرف ما تحتاجه حسب الحاجة.',
                    ],
                ],
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'الاتصالات والإنترنت',
                'icon' => 'fas fa-wifi',
                'content_type' => 'list',
                'items' => [
                    'هناك ثلاثة شركات اتصالات رئيسية: سيرياتل، MTN، وSyriatel',
                    'يمكن شراء شريحة SIM من أي من مكاتب الشركات',
                    'الإنترنت متاح في معظم الفنادق والمقاهي',
                    'الإنترنت قد يكون بطيئاً في بعض المناطق',
                    'يُنصح بتنزيل الخرائط قبل السفر',
                ],
                'custom_sections' => [
                    [
                        'title' => 'باقات الإنترنت والاتصالات',
                        'content' => 'توفر شركات الاتصالات باقات متنوعة للاتصالات والإنترنت. الباقات الشهرية توفر عادة دقائق مجانية وبيانات إنترنت. يُنصح بشراء باقة مناسبة لفترة إقامتك.',
                    ],
                ],
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'المواصلات',
                'icon' => 'fas fa-bus',
                'content_type' => 'list',
                'items' => [
                    'التاكسي متاح في جميع المدن الرئيسية',
                    'الحافلات العامة هي وسيلة نقل اقتصادية',
                    'يمكن استئجار سيارة من شركات التأجير الدولية',
                    'يُنصح بالاتفاق على السعر قبل ركوب التاكسي',
                    'تطبيقات النقل محدودة أو غير متاحة',
                ],
                'custom_sections' => [
                    [
                        'title' => 'استئجار السيارة',
                        'content' => 'للاستئجار سيارة، تحتاج إلى رخصة قيادة دولية. يُنصح بالتأمين الشامل. القيادة في سوريا قد تكون صعبة في بعض المناطق، خاصة في المدن الكبيرة بسبب حركة المرور.',
                    ],
                ],
                'order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'الثقافة والعادات',
                'icon' => 'fas fa-hands-praying',
                'content_type' => 'text',
                'content' => 'سوريا بلد متنوع ثقافياً ودينياً. يُنصح باحترام العادات والتقاليد المحلية. الملابس المحتشمة مُفضلة خاصة عند زيارة المساجد والكنائس. المصافحة باليد هي التحية المعتادة. عند الدخول إلى المنازل، يُفضل خلع الأحذية.',
                'custom_sections' => [
                    [
                        'title' => 'زيارة المواقع الدينية',
                        'content' => 'عند زيارة المساجد، يُطلب من النساء ارتداء الحجاب وتغطية الرأس والذراعين والساقين. عند زيارة الكنائس، يُفضل الملابس المحتشمة. يُمنع التصوير في بعض المواقع الدينية، يُنصح بطلب الإذن أولاً.',
                    ],
                    [
                        'title' => 'المعاملات الاجتماعية',
                        'content' => 'الشعب السوري معروف بكرمه وحسن الضيافة. إذا دُعيت إلى منزل، من المهذب إحضار هدية صغيرة مثل الحلويات أو الزهور. من غير المعتاد رفض دعوة إلى الطعام أو القهوة.',
                    ],
                ],
                'order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'الطعام والمطاعم',
                'icon' => 'fas fa-utensils',
                'content_type' => 'list',
                'items' => [
                    'المطبخ السوري غني ومتنوع ومن أشهره في المنطقة',
                    'الوجبات الرئيسية عادة ما تكون كبيرة ومشبعة',
                    'يُنصح بتجربة المأكولات المحلية في المطاعم التقليدية',
                    'الشاي والقهوة جزء من الثقافة المحلية',
                    'المعاملات النقدية شائعة في معظم المطاعم',
                ],
                'custom_sections' => [
                    [
                        'title' => 'أطباق لا تفوتها',
                        'content' => 'من الأطباق التي يجب تجربتها: الكبابة الحلبية، الفتوش، المقلوبة، المحاشي، البرغل، والحمص. الحلويات السورية شهيرة مثل البقلاوة والكنافة. لا تنس تجربة العصير الطازج والآيس كريم التقليدي.',
                    ],
                ],
                'order' => 6,
                'is_active' => true,
            ],
            [
                'title' => 'الأمان والصحة',
                'icon' => 'fas fa-shield-alt',
                'content_type' => 'text',
                'content' => 'يُنصح بالحصول على تأمين سفر شامل قبل السفر. تحقق من التطعيمات المطلوبة قبل السفر. احمل نسخة من جواز سفرك وأوراقك المهمة. تجنب المناطق غير الآمنة واتبع إرشادات السلطات المحلية.',
                'custom_sections' => [
                    [
                        'title' => 'الصحة والرعاية الطبية',
                        'content' => 'يُنصح بشرب الماء المعبأ فقط. تجنب الطعام من الباعة المتجولين غير المعروفين. تأكد من وجود تأمين طبي شامل. المستشفيات الحكومية متاحة لكن قد تكون مزدحمة، المستشفيات الخاصة توفر رعاية أفضل لكنها أغلى.',
                    ],
                    [
                        'title' => 'نصائح الأمان',
                        'content' => 'احتفظ بنسخة رقمية من أوراقك المهمة. تجنب المشي ليلاً في المناطق غير المعروفة. احتفظ برقم السفارة أو القنصلية لبلدك. اتبع إرشادات السلطات المحلية وأخبر شخصاً موثوقاً بخطة سفرك.',
                    ],
                ],
                'order' => 7,
                'is_active' => true,
            ],
            [
                'title' => 'المناخ والملابس',
                'icon' => 'fas fa-tshirt',
                'content_type' => 'list',
                'items' => [
                    'المناخ معتدل بشكل عام، لكن يختلف حسب المنطقة',
                    'الصيف حار وجاف، الشتاء بارد وممطر',
                    'يُنصح بحمل ملابس مناسبة للموسم',
                    'في المدن الساحلية، الجو معتدل معظم السنة',
                    'في المناطق الجبلية، الجو بارد في الشتاء',
                ],
                'custom_sections' => [
                    [
                        'title' => 'ما يجب إحضاره',
                        'content' => 'في الصيف: ملابس قطنية خفيفة، قبعة، نظارات شمس، واقي شمس. في الشتاء: معطف دافئ، ملابس داخلية حرارية، وشاح وقبعة. على مدار السنة: أحذية مريحة للمشي، ملابس محتشمة للمواقع الدينية، وبطاريات احتياطية للكاميرا.',
                    ],
                ],
                'order' => 8,
                'is_active' => true,
            ],
            [
                'title' => 'اللغة والتواصل',
                'icon' => 'fas fa-language',
                'content_type' => 'text',
                'content' => 'اللغة العربية هي اللغة الرسمية، لكن اللغة الإنجليزية تُستخدم في الفنادق والمطاعم السياحية. الفرنسية مفهومة في بعض المناطق. تعلم بعض الكلمات العربية الأساسية سيساعدك في التواصل ويُظهر احترامك للثقافة المحلية.',
                'custom_sections' => [
                    [
                        'title' => 'كلمات عربية مفيدة',
                        'content' => 'مرحباً (hello)، شكراً (thank you)، لو سمحت (please)، مع السلامة (goodbye)، كم السعر؟ (how much?)، أين؟ (where?)، مرحباً بك (welcome)، صباح الخير (good morning)، مساء الخير (good evening).',
                    ],
                ],
                'order' => 9,
                'is_active' => true,
            ],
            [
                'title' => 'التسوق والهدايا التذكارية',
                'icon' => 'fas fa-shopping-bag',
                'content_type' => 'list',
                'items' => [
                    'المساومة على الأسعار شائعة في الأسواق التقليدية',
                    'المشغولات اليدوية والتحف من أفضل الهدايا التذكارية',
                    'الأقمشة الحريرية والسجاد التقليدي شهيران',
                    'الصابون الحلبي التقليدي من أشهر الهدايا',
                    'المشروبات والمأكولات المحلية خيارات جيدة للهدايا',
                ],
                'custom_sections' => [
                    [
                        'title' => 'أفضل أماكن التسوق',
                        'content' => 'السوق المسقوف في حلب هو من أقدم الأسواق في العالم. سوق الحميدية في دمشق مشهور بالملابس والمشغولات اليدوية. في المدن الكبيرة، هناك مراكز تسوق حديثة. لا تنس زيارة محلات الصابون الحلبي والصناعات التقليدية.',
                    ],
                ],
                'order' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($travelBasics as $basic) {
            // معالجة نوع المحتوى
            $data = [
                'title_ar' => $basic['title'],
                'title_en' => $titleTranslations[$basic['title']] ?? $basic['title'],
                'icon' => $basic['icon'],
                'order' => $basic['order'],
                'is_active' => $basic['is_active'],
            ];

            if ($basic['content_type'] === 'list') {
                $data['items_ar'] = $basic['items'];
                $data['items_en'] = array_map(
                    fn (string $item) => $itemTranslations[$item] ?? $item,
                    $basic['items']
                );
                $data['content_ar'] = null;
                $data['content_en'] = null;
            } else {
                $data['content_ar'] = $basic['content'];
                $data['content_en'] = isset($basic['content'])
                    ? ($contentTranslations[$basic['content']] ?? $basic['content'])
                    : null;
                $data['items_ar'] = null;
                $data['items_en'] = null;
            }

            // إضافة الأقسام المخصصة إذا كانت موجودة
            $data['custom_sections_ar'] = isset($basic['custom_sections']) && !empty($basic['custom_sections'])
                ? $basic['custom_sections']
                : null;
            $data['custom_sections_en'] = isset($basic['custom_sections']) && !empty($basic['custom_sections'])
                ? array_map(function (array $section) use ($sectionTitleTranslations, $sectionContentTranslations) {
                    return [
                        'title' => isset($section['title']) ? ($sectionTitleTranslations[$section['title']] ?? $section['title']) : null,
                        'content' => isset($section['content']) ? ($sectionContentTranslations[$section['content']] ?? $section['content']) : null,
                    ];
                }, $basic['custom_sections'])
                : null;

            TravelBasic::updateOrCreate(
                ['order' => $basic['order']],
                $data
            );
        }
    }
}