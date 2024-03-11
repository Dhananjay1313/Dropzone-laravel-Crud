<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class Dropzonecontroller extends Controller
{
    public function add(Request $request)
    {
        $responseData = $request->all();

        $hid = $request->all()['hid'];

        $folderName = $responseData['folder'];

        $allimage = $responseData["allimage"];

        if ($hid != "") {
            $updaterecord = $request->all();
            $updatedata = Person::where('id', $hid);
            // $imageNames = explode(",", $responseData['allimage']);

            $dataToUpdate = [
                "firstname" => $updaterecord["firstname"],
                'lastname' => $updaterecord['lastname'],
                'gender' => $updaterecord['gender'],
                'status' => $updaterecord['status'],
                'language' => $updaterecord['language']
            ];
            $updatedata->update($dataToUpdate);

            if($allimage != ""){
            $allimage = $responseData["allimage"];
            $imageNames = explode(",", $allimage);

            Image::where('main_id', $hid)->delete(); 

            foreach ($imageNames as $imageName) {
                $image = new Image();
                $image->main_id = $hid;
                $image->image = $imageName;
                $image->save();
            }
        }


        } else {
            $person = new Person();
            $person->firstname = $request->firstname;
            $person->lastname = $request->lastname;
            $person->gender = $request->gender;
            $person->status = $request->status;
            $person->language = $request->language;
            $imageNames = explode(",", $responseData['allimage']);

            $person->save();

            $id = DB::getPdo()->lastInsertId();
            $oldFolder = public_path("image/" . $folderName);

            $newFolder = public_path("image/" . $id);

            if (file_exists($oldFolder)) {
                rename($oldFolder, $newFolder);
            }

            foreach ($imageNames as $imageName) {
                $main_id = $person->id;
                $image = new Image();
                $image->main_id = $main_id;
                $image->image = $imageName;
                $image->save();
            }
        }
    }

    public function list()
    {
        $list = Person::all();

        $aaa = [];
        foreach ($list as $value) {
            if ($value->status == '0') {
                $row['firstname'] = $value->firstname;
                $row['lastname'] = $value->lastname;
                $row['gender'] = $value->gender;
                $row['language'] = $value->language;
                $row['action'] = "<button class='btn btn-success' id='edit_id' data-id=" . $value->id . ">Edit</button>
            <button class='btn btn-warning' id='delete_id' data-id=" . $value->id . ">Delete</button>";
                array_push($aaa, $row);
            }
        }
        return response()->json(['data' => $aaa]);
    }

    public function upload(Request $request){
        
        $hid = $request->input('hidden_id', '');
        $imgdata = $request->file();
        if (!empty($hid)) {
            $folderName = $hid;
        } else {
            $folderName = time() . "_temp";
        }

        $path = public_path("image/" . $folderName);
        $allimg = $imgdata['file'];
        $allimgname = [];

        foreach ($allimg as $file) {
            $name = $file->getClientOriginalName();
            $file->move($path, $name);
            array_push($allimgname, $name);
        }

        $alldata = [$folderName, $allimgname];
        return $alldata;
    }

    public function deleteupload(Request $request){

    $data = $request->all();

    $imageName = $data['image'];
    $folderName = $data['folder'];

    $path = public_path("image/$folderName/$imageName");
    
    if (file_exists($path)) {
        unlink($path);    
    } 
    return $imageName;
    }

    public function edit(Request $request)
    {
        $edit_id = $request->all();

        $id = $edit_id['id'];
        $image = Image::select('*')->where('main_id', $id)->get()->toArray();

        $imageArray = [];
        $img1 = "";

        foreach ($image as $img) {
            $img1 .= "<div class='image col col-sm-6'>";
            $img1 .= "<img src='" . asset("image/$id/" . $img['image']) . "' alt='Image' height='100px' weight='auto' style='margin-top: 2%;margin-bottom: 1%;border-radius: 12px;'><br>
                 <button style='margin-left: 23%;' type='button' class='btn btn-warning delete' data-id='" . $img['id'] . "' id='" . $id . "''>Delete</button> ";
            $img1 .= "</div>";
            // $imgname = $img['image'];
            array_push($imageArray, $img['image']);
        }
        $person = Person::find($id)->toArray();
        $alldata = [$person, $img1, $imageArray];
        return $alldata;
    }

    public function deleteimage(Request $request)
    {
        $id = $request->input('id');
        $image = Image::where('main_id', $id);
        if ($image) {
            $imagePath = public_path('image/' . $id);
            if (file_exists($imagePath) && is_dir($imagePath)) {
                $dirHandle = opendir($imagePath);

                while (false !== ($file = readdir($dirHandle))) {
                    if ($file != '.' && $file != '..') {
                        unlink($imagePath . '/' . $file);
                    }
                }

                closedir($dirHandle);

                rmdir($imagePath);
            }
            $image->delete();
            $person = Person::findOrFail($id);
            if ($person) {
                $person->delete();
            }
        }
    }

    public function deleteimg(Request $request){

        $responseData = $request->all();
        $imgname_for_deleteIn_folder = Image::where("id", $responseData['img_id'])->get()->toArray();

        $id = $imgname_for_deleteIn_folder[0]['main_id'];
        $imgname_for_folder = $imgname_for_deleteIn_folder[0]['image'];

        $images = Image::where("id", $responseData['img_id'])->delete();

        // $remainingImages = Image::where("main_id", $id)->count();
        // if ($remainingImages == 0) {
        //     $response = "";
        // } else {
        //     $response = $imgname_for_folder;
        // }

        if ($images) {
            $imagePath = public_path('image/' . $id . '/' . $imgname_for_folder);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        return $imgname_for_folder;
    }
}
