<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $news = News::latest('id');


        if ($request->get('keyword')) {
            $news = $news->where('name', 'like', '%' . $request->keyword . '%');
        }
        $news = $news->paginate(10);
        return view('admin.news.list', compact('news'));
    }
    public function create()
    {
        return view('admin.news.create');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'published_at' => 'required'
        ]);
        if ($validator->passes()) {
            $news = new News();
            $news->title = $request->title;
            $news->description = $request->description;
            $news->published_at = $request->published_at;
            $news->save();
            $oldImage = $news->image;


            // Save Image Here
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                if ($tempImage) {
                    $extArray = explode('.', $tempImage->name);
                    $ext = last($extArray);

                    $newImageName = $news->id . '-' . time() . '.' . $ext;
                    $sPath = public_path() . '/temp/' . $tempImage->name;
                    $dPath = public_path() . '/uploads/news/' . $newImageName;

                    // Kiểm tra xem tệp ảnh có tồn tại và có thể đọc được không
                    if (file_exists($sPath) && is_readable($sPath)) {
                        // Sao chép tệp ảnh đến thư mục chính

                        if (File::copy($sPath, $dPath)) {
                            // Tạo thumbnail
                            $thumbnailPath = public_path() . '/uploads/news/thumb/' . $newImageName;

                            $img = Image::make($dPath);
                            $img->resize(300, 200);
                            $img->save($thumbnailPath);

                            // Lưu tên ảnh vào cơ sở dữ liệu
                            $news->image = $newImageName;
                            $news->save();

                            Session::flash('success', 'Image added successfully!');

                            // Delete Old Image Here
                            File::delete(public_path() . '/uploads/news/thumb' . $oldImage);
                            File::delete(public_path() . '/uploads/news/' . $oldImage);


                            return response()->json([
                                'status' => true,
                                'message' => "News added successfully"
                            ]);
                        }
                    }
                }
            }
            Session::flash('success', 'News created successfully');

            return response()->json([
                'status' => true,
                'message' => 'News created successfully'
            ]);
        }
    }
    public function edit($id)
    {
        $new = News::find($id);
        if (empty($new)) {
            return response()->json([
                'status' => false,
                'message' => 'New not found'
            ]);
        }
        $data['new'] = $new;
        return view('admin.news.edit', $data);
    }
    public function update(Request $request, $id)
    {
        $new = News::find($id);

        if (empty($new)) {
            return response()->json([
                'status' => false,
                'message' => 'News not found'
            ]);
        }

        // Cập nhật dữ liệu khi bản ghi tồn tại
        $new->title = $request->title;
        $new->description = $request->description;
        $new->published_at = $request->published_at;
        $new->save();

        Session::flash('success', 'News updated successfully');

        return response()->json([
            'status' => true,
            'message' => 'News updated successfully'
        ]);
    }

    public function destroy($id, Request $request)
    {
        $news = News::find($id);
        if (empty($news)) {
            Session::flash('error', 'News not found');
            return response()->json([
                'success' => false,
                'message' => 'News not found'
            ]);
        }
        $news->delete();
        Session::flash('success', 'News deleted successfully');
        return response()->json([
            'success' => true,
            'message' => 'News deleted successfully'
        ]);
    }
}
