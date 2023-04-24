<?php

namespace App\Models\Repositories\Categories;

interface CategoryInterface {
    
    public function getCategory($filters);
    public function getCategories($filters);
//     public function getSubCategories($filters);
    public function saveOrEditCategory($data);
    public function getCategoriesForSearch($data);
//     public function getSubCategory($filters);
//     public function saveOrEditSubCategory($data);
//     public function getSubCategoriesForSearch($filters);
    public function categoryDelete($id);
//     public function subCategoryDelete($id);
    public function statusCategoryUpdate($data);
//     public function statusSubCategoryUpdate($data);
    
}