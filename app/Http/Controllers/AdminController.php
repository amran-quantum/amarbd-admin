<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Category;
use App\About;
use App\Contact;
use App\Dashboard;
use App\Privation;
use App\Post;


class AdminController extends Controller
{
    public function home(Request $request)
    {
        return view('welcome');
    }

    public function showAboutText()
    {

        $about =  About::all();
        return response()->json([
            'about' => $about,
            'success' => true
        ], 200);
    }

    public function editAboutText(Request $request)
    {
        $data = $request->all();
        About::where('id', $data['id'])->update([
            'post_description' => $data['post_description'],
            'bn_post_description' => $data['bn_post_description']
        ]);
        $about = About::where('id', $data['id'])->first();
        return response()->json([
            'about' => $about,
            'success' => true
        ], 200);
    }

    public function showContactText()
    {
        $contact =  Contact::all();
        return response()->json([
            'contact' => $contact,
            'success' => true
        ], 200);
    }

    public function editContactText(Request $request)
    {
        $data = $request->all();
        Contact::where('id', $data['id'])->update($data);
        $contact = Contact::where('id', $data['id'])->first();
        return response()->json([
            'contact' => $contact,
            'success' => true
        ], 200);
    }
    public function showPrivacyText()
    {
        $privacy =  Privation::all();
        return response()->json([
            'privacy' => $privacy,
            'success' => true
        ], 200);
    }

    public function editPrivacyText(Request $request)
    {
        $data = $request->all();
        Privation::where('id', $data['id'])->update($data);
        $privacy = Privation::where('id', $data['id'])->first();
        return response()->json([
            'privacy' => $privacy,
            'success' => true
        ], 200);
    }
    public function dashboard(Request $request)
    {
        $total_news = Post::count();
        $published_news = Post::where("publish_status",1)->count();
        $total_journalist = User::where("userTYpe","journalist")->count();
        return response()->json([
            'total_news' => $total_news,
            'published_news' => $published_news,
            'total_journalist' => $total_journalist,
            'success' => true
        ], 200);
    }

    public function showCategory(Request $request)
    {
        $category = Category::paginate(20);
        return response()->json([
            'category' => $category,
            'success' => true
        ], 200);
    }
    public function showCategoryApi(Request $request)
    {
        $category = Category::all();
        return response()->json([
            'category' => $category,
            'success' => true
        ], 200);
    }
    public function showNews(Request $request)
    {
        $user = User::where('id', $request['user_id'])->first();
        $news = Post::with('user');
        if ($user->userType == 'journalist') {
            $news = $news->where('user_id', $request['user_id']);
        }
        if(isset($request['publish_status']) && number_format($request['publish_status']) == 0){
            $news = $news->where('publish_status',0)->orderBy('updated_at','desc')->paginate(10);
        }
        else if(isset($request['publish_status']) && number_format($request['publish_status']) == 1){
            $news = $news->where('publish_status',1)->orderBy('updated_at','desc')->paginate(10);
        }
        else{
            $news = $news->orderBy('updated_at','desc')->paginate(10);
        }
        return response()->json([
            'news' => $news,
            'success' => true
        ], 200);
    }
    public function showNewsApi(Request $request)
    {
        $page = number_format($request['page']) ? number_format($request['page']) : 1;
        $limit = number_format($request['limit']) ?  number_format($request['limit']) : 10;
        $news = Post::with('user')->orderBy("updated_at", "desc")->skip(($page - 1) * $limit)->take($limit)->get();
        $total_news = Post::count();
        return response()->json([
            'news' => $news,
            'total_news' => $total_news,
            'success' => true
        ], 200);
    }
    public function showNewsQueryApi(Request $request)
    {
        $page = number_format($request['page']) ? number_format($request['page']) : 1;
        $limit = number_format($request['limit']) ?  number_format($request['limit']) : 10;
        $news = Post::where('category',$request['category'])->with('user')->orderBy("updated_at", "desc")->skip(($page-1)*$limit)->take($limit)->get();
        $total_news = Post::where('category',$request['category'])->count();
        return response()->json([
            'news' => $news,
            'total_news' => $total_news,
            'success' => true
        ], 200);
    }

    public function addNews(Request $request)
    {
        $data = $request->all();
        $count = Post::where('headline', $data['headline'])->first();
        if ($count) {
            return response()->json([
                'msg' => 'This headline is already used!',
                'success' => false
            ], 422);
        }
        $news = Post::create($data);
        return response()->json([
            'news' => $news,
            'success' => true
        ], 200);
    }
    public function editNews(Request $request)
    {
        $data = $request->all();
        $g = Post::where('id', $data['id'])->update([
            'headline' => $data['headline'],
            'news' => $data['news'],
            'category' => $data['category'],
            'division' => $data['division'],
            'district' => $data['district'],
            'bn_headline' => $data['bn_headline'],
            'bn_news' => $data['bn_news'],
            'bn_category' => $data['bn_category'],
            'bn_division' => $data['bn_division'],
            'bn_district' => $data['bn_district'],
            'banner' => $data['banner'],
            'video_link' => $data['video_link'],
            'user_id' => $data['user_id']
        ]);
        if ($g) {
            $news = Post::with('user')->where('id', $data['id'])->first();
        }
        return response()->json([
            'news' => $news,
            'success' => true
        ], 200);
    }

    // approve news
    public function approveNews(Request $request)
    {
        $user = Auth::user();
        if($user->userType != "admin"){
            return response()->json([
                'msg' => "Only admin can approve news",
                'success' => false
                ],401);
        }

        $data = $request->all();
        $g = Post::where('id', $data['id'])->update([
            'publish_status' => 1
        ]);
        if ($g) {
            $news = Post::with('user')->where('id', $data['id'])->first();
        }
        return response()->json([
            'news' => $news,
            'success' => true
        ], 200);
    }
    public function deleteNews(Request $request)
    {
        $data = $request->all();
        $news = Post::where('id', $data['id'])->delete();

        return response()->json([
            'success' => true
        ], 200);
    }
    public function addCategory(Request $request)
    {
        $data = $request->all();
        $count = Category::where('name', $data['name'])->count();
        if ($count > 0) {
            return response()->json([
                'msg' => 'This name is already used!',
                'success' => false
            ], 422);
        }
        $category = Category::create($data);
        return response()->json([
            'category' => $category,
            'success' => true
        ], 200);
    }
    public function editCategory(Request $request)
    {
        $data = $request->all();
        $count = Category::where('name', $data['name'])->where('id', '!=', $data['id'])->first();
        if ($count) {
            return response()->json([
                'msg' => 'This name is already used!',
                'success' => false
            ], 422);
        }
        $g = Category::where('id', $data['id'])->update([
            'name' => $data['name'],
            'bn_name' => $data['bn_name']
        ]);
        if ($g) {
            $category = Category::where('id', $data['id'])->first();
        }
        return response()->json([
            'category' => $category,
            'success' => true
        ], 200);
    }
    public function deleteCategory(Request $request)
    {
        $data = $request->all();
        $category = Category::where('id', $data['id'])->delete();

        return response()->json([
            'category' => $category,
            'success' => true
        ], 200);
    }

    //file upload function
    public function image_upload(Request $request)
    {

        request()->file('img')->store('/uploads');
        $pic =  "/uploads/" . $request->img->hashName();
        return $pic;
    }
}
