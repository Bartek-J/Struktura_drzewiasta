<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Relation;
use Illuminate\Http\Request;
use App\Http\Requests\ChangeRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\MoveRequest;
use App\Http\Requests\NewRequest;

use function PHPUnit\Framework\isNull;

class TreeController extends Controller
{
    public function index()
    {
        $categories = Category::with('relationsAsChild')->orderBy('id', 'asc')->get();
        $allCategories = Category::select('id', 'name')->get();
        if (!empty($categories)) {
            $categoriesAll = array();
            foreach ($allCategories as $cat) {
                $categoriesAll[$cat->id] = $cat->name;
            }
            foreach ($categories as $category) {
                $tmp = '';
                foreach ($category->relationsAsChild as $rel) {
                    $tmp = $tmp . '/' . $categoriesAll[$rel->parent_id];
                }
                $wynik[$category->id] = ['path' => $tmp, 'name' => $category->name, 'id' => $category->id, 'level' => substr_count($tmp, '/') - 1];
            }
            asort($wynik);
        } else {
            $wynik = 'empty';
        }
        return view('tree')->with(compact('wynik', 'categories'));
    }

    public function add(NewRequest $request)
    {
        $category = new Category;
        $category->name = $request->name;
        $category->save();
        $i = 0;
        if ($request->parent != 'top') {
            $parentConnections = Relation::select('parent_id', 'distance')->where('child_id', $request->parent)->get();
            foreach ($parentConnections as $conn) {
                $allConnections[$i] = ['parent_id' => $conn->parent_id, 'child_id' => $category->id, 'distance' => $conn->distance + 1];
                $i++;
            }
            $allConnections[$i] = ['parent_id' => $request->parent, 'child_id' => $category->id, 'distance' => 1];
        }
        $allConnections[$i] = ['parent_id' => $category->id, 'child_id' => $category->id, 'distance' => 0];
        Relation::insert($allConnections);
        return back()->with([
            'status' => [
                'type' => 'success',
                'content' => 'Pomyślnie dodano nową kategorię!'
            ]
        ]);
    }

    public function delete(DeleteRequest $request)
    {
        $category = Relation::select('child_id')->where('parent_id', $request->deleted)->get()->toArray();
        Relation::whereIn('child_id', $category)->delete();
        Category::whereIn('id', $category)->delete();
        return back()->with([
            'status' => [
                'type' => 'success',
                'content' => 'Pomyślnie usunięto kategorię i jej podkategorie!'
            ]
        ]);;
    }

    public function move(MoveRequest $request)
    {
        $oldConnections = Relation::select('parent_id')->where('child_id', $request->who)->where('distance', '>', 0)->get()->toArray();
        if($request->where == 'top')
        {
            $children=Relation::select('child_id')->where('parent_id', $request->who)->get()->toArray();
            Relation::whereIn('parent_id', $oldConnections)->whereIn('child_id', $children)->delete();
            return back()->with([
                'status' => [
                    'type' => 'success',
                    'content' => 'Pomyślnie przeniesiono kategorię oraz wszystkie jego podkategorie!'
                ]
            ]);  
        }
        if(!empty(Relation::select('child_id')->where('parent_id', $request->who)->where('child_id',$request->where)->get()->toArray()))
        {
            return back()->with([
                'status' => [
                    'type' => 'danger',
                    'content' => 'Nie można przenieść kategori do jej własnej podkategorii!'
                ]
            ]); 
        }
        $categories = Relation::select('child_id', 'distance')->where('parent_id', $request->who)->orderBy('distance', 'asc')->get();
        $parentConnections = Relation::select('parent_id', 'distance')->where('child_id', $request->where)->get();
        $i = 0;
        $j = 0;
        foreach ($categories as $category) {
            $children[$j] = [$category->child_id];
            foreach ($parentConnections as $parentCon) {
                $allConnections[$i] = ['parent_id' => $parentCon->parent_id, 'child_id' => $category->child_id, 'distance' => $category->distance + $parentCon->distance + 1];
                $i++;
            }
            $j++;
        }
        Relation::whereIn('parent_id', $oldConnections)->whereIn('child_id', $children)->delete();
        Relation::insert($allConnections);
        return back()->with([
            'status' => [
                'type' => 'success',
                'content' => 'Pomyślnie przeniesiono kategorię oraz wszystkie jego podkategorie!'
            ]
        ]);
    }

    public function changeName(ChangeRequest $request)
    {
        $category = Category::findOrFail($request->change_id);
        $category->name = $request->name1;
        $category->save();
        return back()->with([
            'status' => [
                'type' => 'success',
                'content' => 'Pomyślnie zmieniono nazwę!'
            ]
        ]);
    }
}
