<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Price;
use App\Models\Comment;
use App\Models\Like;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $product = Product::select()->get();
        return $this->returnData('product', $product);
    }

   /******************************************************************************************************* */


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\product  $product
     * @return \Illuminate\Http\Response
     */
    
    
    public function add(Request $request)
    { 
    $validator = Validator::make($request->all(), [
        'name'=>'required',
        'o_price'=>'required|integer',
        'exp_date'=>'required',
        'category'=>'required',
        'description'=>'required',
        'phone_number'=>'required',
        'quantity'=>'required|integer',
        's1'=>'required|integer',
         'Counter_days1'=>'required|integer',
         's2'=>'required|integer',
         'Counter_days2'=>'required|integer',
          's3'=>'required|integer',  
        'image_url' => 'required|image:jpeg,png,bmp,jpg,gif,svg|max:2048'
    ]);
    if ($validator->fails()) {
        return $this->returnError(0000, $validator->errors());
    }
    $uploadFolder = 'products';
    $image = $request->file('image_url');
    $image_uploaded_path = $image->store($uploadFolder, 'public');
	$name = request()->name;
    $category_id="x";
    
		$image = Storage::disk('public')->url($image_uploaded_path);
        $category = request()->category;
        $categores=Category::select()->get();
        for ($i=0;$i<count($categores);$i++)
        {
            if ($categores[$i]->name ==$category)
            {
                $category_id=$categores[$i]->id;
                break;
            }

        }
        if($category_id=="x")
        {
            $Category=Category::create([
                'name'=>$category,
            ]);
            $category_id=$Category->id;
        }
		$exp_date =Carbon::parse(request()->exp_date)->toDateString(); 
        $description = request()->description;
        $phone_number=request()->phone_number;
        if (request()->quantity == Null)
       { $quantity=1;}
        else
		{$quantity = request()->quantity;}
		$o_price = request()->o_price;
    $Counter_days1=request()->Counter_days1;
    $Counter_days2=request()->Counter_days2;
          $s1=request()->s1;
          $s2=request()->s2;
          $s3=request()->s3;
          $d1=Carbon::parse($exp_date)->subDays($Counter_days1)->toDateString();
          $d2=Carbon::parse($exp_date)->subDays($Counter_days2)->toDateString();
          $s=($s1*$o_price)/100;
          $s_price=$o_price-$s;
        $u_id = Auth::guard('user-api')->user()->id;
        $product = product::create([
			'name' => $name,
			'image_url' => $image,
            'category' => $category,
            'exp_date' => $exp_date,
			'description' => $description,
            'quantity' => $quantity,
			'o_price' => $o_price,
            's_price' => $s_price,
            'phone_number'=>$phone_number,
            'user_id' => $u_id,
            'category_id' => $category_id,
            's1'=>$s1,
			'Counter_days1'=>$Counter_days1,
            's2'=>$s2,
            'Counter_days2'=>$Counter_days2,
            's3'=>$s3,

        ]);
        $price=Price::create([
            's1'=>$s1,
             'd1'=>$d1,
             's2'=>$s2,
             'd2'=>$d2,
              's3'=>$s3,
             'product_id'=>$product->id,
             
        ]);
	
		return $this->returnData("product",$product,"Add successfully.");
    }



    /******************************************************************************************************* */



    public function getProductById($id)
    {


        $product = Product::select()->find($id);
        if (!$product)
            return $this->returnError('001', 'This product is not available');
            $user= Auth::guard('user-api')->user();
            $user_id=$user->id;
            $myiduser=$product->user_id;
          
           if (($user_id)!=($myiduser))
           { 

               $count=$product->count_views;
               $count++;
               $product->update([
                'count_views'=>$count,
            ]);
           }
        return $this->returnData('product', $product);
    }



    /******************************************************************************************************* */



    public function getAllProducts()
    {
          $this->checkDates();
           
        $product = Product::get();
     
        return $this->returnData('product', $product);
    }


    /******************************************************************************************************* */




    public function destroy($id)
    {
        $product = Product::find($id);
        //return $product;
        if (!$product)
            return $this->returnError('001', 'This product is not available');
            $user= Auth::guard('user-api')->user();
            $user_id=$user->id;
            $myiduser=$product->user_id;
           if (($user_id)!=($myiduser))
           { return $this->returnError('X0000','This product is not for you dear');}
           else 
      {  $product->delete();
        return $this->returnData('product', $product,'Deleted successfully');}
    }

/******************************************************************************************************* */



public function edit(Request $request,$id)
{
    
     $product = Product::find($id);  // search in given table id only
    if (!$product)
   { return $this->returnError('E000','This product is not available');}
       $user= Auth::guard('user-api')->user();
        $user_id=$user->id;
        $myiduser=$product->user_id;
      
       if (($user_id)!=($myiduser))
       { return $this->returnError('X0000','This product is not for you dear');}
       else 
  {  $Product = Product::select(
      'id',
    'name',
    'o_price',
    's_price',
    'description',
    'phone_number',
    'image_url',
    'quantity',
    'category',
    's1',
    'Counter_days1',
    's2',
    'Counter_days2',
    's3'
    )->find($id);

    return  $this->returnData('Product',$Product);
  }

}



/******************************************************************************************************* */




public  function update( Request $request,$id)
{
    $validator = Validator::make($request->all(), [
        'name'=>'required',
        'o_price'=>'required|integer',
        'category'=>'required',
        'description'=>'required',
        'phone_number'=>'required',
        'quantity'=>'required|integer',
        's1'=>'required|integer',
         'Counter_days1'=>'required|integer',
         's2'=>'required|integer',
         'Counter_days2'=>'required|integer',
          's3'=>'required|integer',  
        'image_url' => 'required|image:jpeg,png,bmp,jpg,gif,svg|max:2048'
        
    ]);
    if ($validator->fails()) {
        return $this->returnError(0000, $validator->errors());
    }
    $product = Product::find($id);  // search in given table id only
    if (!$product)
   { return $this->returnError('E000','هذ المنتج غير موجود');}
       $user= Auth::guard('user-api')->user();
        $user_id=$user->id;
        $myiduser=$product->user_id;

       if (($user_id)!=($myiduser))
       { return $this->returnError('X0000','هذا المنتج ليس لك عزيزي وشكراً');}

    //update data
    $name=request()->name;
    $o_price=request()->o_price;
    $description=request()->description;
    $phone_number=request()->phone_number;
    $s1=request()->s1;
    $Counter_days1=request()->Counter_days1;
    $s2=request()->s2;
    $Counter_days2=request()->Counter_days2;
    $s3=request()->s3;
    $d1=Carbon::parse($product->exp_date)->subDays($Counter_days1)->toDateString();
          $d2=Carbon::parse($product->exp_date)->subDays($Counter_days2)->toDateString();
          $s=($s1*$o_price)/100;
          $s_price=$o_price-$s;
    if (request()->quantity == Null)
       { $quantity=1;}
        else
    {$quantity=request()->quantity;}
    
    $uploadFolder = 'products';
    $image = $request->file('image_url');
    $image_uploaded_path = $image->store($uploadFolder, 'public');
    $image = Storage::disk('public')->url($image_uploaded_path);
    $category_id='x';
    $category = request()->category;
        $categores=Category::select()->get();
        for ($i=0;$i<count($categores);$i++)
        {
            if ($categores[$i]->name ==$category)
            {
                $category_id=$categores[$i]->id;
                break;
            }

        }
        if($category_id=="x")
        {
            $Category=Category::create([
                'name'=>$category,
            ]);
            $category_id=$Category->id;
        }
        $price_id=$product->price->id;
        $price=Price::find($price_id);
    $product->update([
        'name'=>$name,
        'image_url'=>$image,
        'category'=>$category,
        'o_price'=>$o_price,
        's_price'=>$s_price,
        'description'=>$description,
        'phone_number'=>$phone_number,
        'category_id'=>$category_id,
        'quantity'=>$quantity,
    ]);
    $price->update([
        's1'=>$s1,
         'd1'=>$d1,
         's2'=>$s2,
         'd2'=>$d2,
          's3'=>$s3,         
    ]);

    return $this->returnSuccessMessage('Updated successfully');
}


/******************************************************************************************************* */




public function serch(Request $request)
{
    $by=request()->by;
      $serch_text=request()->serch_text;
      if($by=='exp_date')
      {
        $serch_date=Carbon::parse($serch_text)->toDateString(); 
          
        $products=Product::where($by,'LIKE','%'.$serch_date.'%')->get();
        return $this->returnData('product',$products);
      }
    $products=Product::where($by,'LIKE','%'.$serch_text.'%')->get();
    return $this->returnData('product',$products);
}




/******************************************************************************************************* */




    public function createcategoy(Request $request )
    {
        $name=request()->name;
        $cat=Category::create([
             'name'=>$name,
        ]);
        return $this->returnSuccessMessage('ok');
    }



    /******************************************************************************************************* */




    public function categoryandproductsbyid($id)
    {
        $this->checkDates();
        $categores=Category::find($id);
        $your_product=$categores->product;
        return $this->returnData('your_product',$your_product);
    }


    /******************************************************************************************************* */



    public function allcategory()
    {
        $categores=Category::select()->get();
        return $this->returnData('categores',$categores);
    }


    /******************************************************************************************************* */



    public function Allcategoryandproducts()
    {
        $this->checkDates();
        $categores=Category::select()->with('product')->get();
        return $this->returnData('categoryandproducts',$categores);
    }


    /******************************************************************************************************* */



    public function sort(Request $request)
    {
        $key=request()->key;
        $tybe=request()->tybe;
        $products=Product::select()->get();
            
        foreach ($products as $k=>$v)
        {
            $b[]=strtolower($v[$key]);
        }
       
        if($tybe=="progressive")
        {
            asort($b);
        }
        elseif($tybe == 'descending' )
        {
            arsort($b);
        }
        else
        {
            return $this->returnError('xxx','The order type is incorrect, please try again !!!');
        }
        foreach($b as $k=>$v)
        {
            $tidy_products[]=$products[$k];
        }
        return $this->returnData('tidy_products',$tidy_products);

    }



    /******************************************************************************************************* */




    public function addcomment(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->returnError(0000, 'You must add a comment');
        }

        $comment=request()->comment;
       
        $user= Auth::guard('user-api')->user();
        $user_id=$user->id;
        $image_user=$user->image_url;
        $coment=Comment::create([
            'comment'=>$comment,
            'product_id'=>$id,
            'user_id'=>$user_id,
            'image_user'=>$image_user,
        ]);
        $product=Product::find($id);
        $count=$product->count_comments;
        $count++;
        $product->update([
         'count_comments'=>$count,
     ]);
        return $this->returnSuccessMessage('Comment added successfully');
    }



    /******************************************************************************************************* */




    public function allcommentswithproduct($id)
    {
        $product=Product::find($id);
        $comments=$product->comment;
        return $this->returnData('comments',$comments);
    }



    /******************************************************************************************************* */




    public function likeAnddeslike($id)
    {
        
      //  $product=Product::find($id);
        $user= Auth::guard('user-api')->user();
        $user_id=$user->id;
        $likee=Product::find($id)->likes()->where('user_id',$user_id)->first();
        if (!$likee)
        {
            Like::create([
                'status'=>true,
                'product_id'=>$id,
                'user_id'=>$user_id
            ]);
            return $this->returnSuccessMessage('Liked successfully');
        }
        elseif($likee->status)
        {
            $like_id=$likee->id;
            $LIKE=Like::find($like_id);
            $LIKE->update([
                'status'=>false
            ]);
            $product=Product::find($id);
        $count=$product->count_likes;
        $count--;
        $product->update([
         'count_likes'=>$count,
     ]);
     return $this->returnSuccessMessage('Successfully disliked');
        }
        elseif(!($likee->status))
        {
            $like_id=$likee->id;
            $LIKE=Like::find($like_id);
            $LIKE->update([
                'status'=>true
            ]);
            $product=Product::find($id);
        $count=$product->count_likes;
        $count++;
        $product->update([
         'count_likes'=>$count,
     ]);
     return $this->returnSuccessMessage('Liked successfully');
        }
        
    }



        /******************************************************************************************************* */




    public function getstatususerlike($id)
    {
        $status=false;
        $user= Auth::guard('user-api')->user();
        $user_id=$user->id;
        $likee=Product::find($id)->likes()->where('user_id',$user_id)->first();
        if (!$likee)
        {
            
            return $this->returnData('status_like',$status,'Dislike');
        }
        elseif($likee->status)
        {
            $status=true;
            return $this->returnData('status_like',$status,'Like');
        }
        elseif(!($likee->status))
        {
            return $this->returnData('status_like',$status,'Dislike');
        }
    }


}
