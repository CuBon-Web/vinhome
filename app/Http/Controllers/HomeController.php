<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\models\product\Category;
use App\models\product\Product;
use App\models\blog\Blog;
use Session;
use App\models\website\Partner;
use App\models\website\WhyChoose;
use App\models\blog\BlogCategory;
use App\models\BannerAds;
use App\models\website\Video;
use App\models\website\Founder;
use App\models\website\Prize;
use App\models\website\AlbumAffter;
use App\models\ReviewCus;
use App\models\PageContent;
use App\models\Services;
use App\models\Project;
use App\models\website\ProcessStep;
use App\models\website\Faq;
use App\models\website\HomePriceItem;
use App\models\website\HomePriceMeta;
use App\models\website\HomeProjectMeta;
use App\models\website\HomeProjectSpec;
use App\models\website\FloorPlan;

class HomeController extends Controller
{
    public function home()
    {
        // $data['blogCateForBlog'] = BlogCategory::where('status', 1)
        //     ->orderBy('id', 'DESC')
        //     ->get()
        //     ->each(function ($cate) {
        //         $cate->setRelation(
        //             'listBlog',
        //             $cate->listBlog()->where('status', 1)->limit(8)->get()
        //         );
        //     });
        $data['hotnews'] = Blog::where([
            ['status','=',1]
        ])->orderBy('id','DESC')->limit(3)->get(['id','title','slug','created_at','image','description']);
        $data['gioithieu'] = PageContent::where('slug', 'gioi-thieu')
            ->whereIn('language', [app()->getLocale(), 'en', 'vi'])
            ->orderByRaw("FIELD(language, ?, 'en', 'vi')", [app()->getLocale()])
            ->first(['id', 'title', 'description', 'image']);
        $data['whyChoose'] = WhyChoose::where('status', 1)->orderBy('sort')->orderBy('id')->get();
        $data['service'] = Services::where(['status'=>1,'status_home'=>1])->get();
        $data['bannerads'] = BannerAds::where(['status'=>1,'status_show'=>'banner_ads'])->get();
        $data['ReviewCus'] = ReviewCus::where(['status'=>1])->get();
        $data['Partner'] = Partner::where(['status'=>1])->get();
        $data['duan'] = Project::where('status',1)->where('show_home',1)->with('cateProject')->limit(10)->get();
        $data['processSteps'] = ProcessStep::where('status',1)->orderBy('sort')->orderBy('id')->get();
        $data['homeFaqs'] = Faq::where('status', 1)->orderBy('sort')->orderBy('id')->get();
        $data['homePrices'] = HomePriceItem::where('status', 1)->orderBy('sort')->orderBy('id')->get();
        $data['homePriceMeta'] = HomePriceMeta::first();
        $data['homeProjectMeta'] = HomeProjectMeta::first();
        $data['homeProjectSpecs'] = HomeProjectSpec::where('status', 1)->orderBy('sort')->orderBy('id')->get();
        $data['gallery'] = AlbumAffter::where('status', 1)->orderBy('sort')->orderBy('id')->get();
        $data['homePro'] = Product::where(['status'=>1,'discountStatus'=>1,'consignment'=>0])
            ->select('id','category','name','discount','price','images','slug','cate_slug','type_slug','description','status_variant','discountStatus','home_status')
            ->with(['cate:id,slug,name'])
            ->inRandomOrder()->limit(18)->get();
        $this->attachVariantPriceRange($data['homePro']);
        $data['featuredPlans'] = $this->featuredFloorPlans();
        return view('home',$data);
    }

    private function featuredFloorPlans()
    {
        return FloorPlan::with('category:id,name')
            ->where('status', 1)
            ->where('featured', 1)
            ->orderBy('sort')
            ->orderBy('id')
            ->limit(6)
            ->get(['id', 'category_id', 'name', 'slug', 'title', 'intro', 'content', 'journal'])
            ->map(function ($plan) {
                preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', (string) $plan->content, $match);
                $journal = is_array($plan->journal) ? $plan->journal : [];

                return (object) [
                    'name' => $plan->name,
                    'code' => trim((string) preg_replace('/^layout\s+/iu', '', $plan->name)),
                    'zone' => optional($plan->category)->name,
                    'intro' => $plan->intro,
                    'image' => $match[1] ?? '',
                    'journalDays' => count($journal),
                    'url' => route('floorPlan', $plan->slug),
                    'journalUrl' => route('floorPlanJournal', $plan->slug),
                ];
            });
    }

    private function attachVariantPriceRange($products): void
    {
        $products = collect($products);
        if ($products->isEmpty()) {
            return;
        }

        $variantProductIds = $products
            ->where('status_variant', 1)
            ->pluck('id')
            ->filter()
            ->values()
            ->all();

        if (empty($variantProductIds)) {
            return;
        }

        $ranges = \App\models\VariantSkuValue::query()
            ->selectRaw('product_id, MIN(price) as min_price, MAX(price) as max_price')
            ->whereIn('product_id', $variantProductIds)
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');

        foreach ($products as $product) {
            $range = $ranges->get($product->id);
            $product->variant_min_price = $range ? (float) $range->min_price : null;
            $product->variant_max_price = $range ? (float) $range->max_price : null;
        }
    }
}
