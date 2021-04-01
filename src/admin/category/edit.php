<?php
namespace Framework;
require("../template.php");
require_once("./include.php");
$messages = array ();
$categoryid= isset ( $_GET ['id'] ) ? ( int ) $_GET ['id'] : 0;
if ($categoryid <= 0 || ! $permission->CheckOperationPermission ( "category", "edit", $GLOBALS ['user']->UserRoleID )) {
    \TAS\Core\Web::Redirect ( "index.php" );
}

$category = new Category($categoryid);
if($categoryid > 0)
{
    if(!$category->IsLoaded())
    {
        \TAS\Core\Web::Redirect("index.php");
    }
}

if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
    
    $d = \TAS\Core\Entity::ParsePostToArray (Category::GetFields ( $categoryid ) );
    if($d['parentid']=='' || $d['parentid']==$categoryid)
    {
        $d['parentid']=0;
    }
    
    $d['editdate']= date("Y-m-d H:i:s");
    
    // Code return for update image in category fields
    $isupdated = $category->Update ( $d );
    
    if (count($_FILES)>0){
        $imageFile = new \TAS\Core\ImageFile();
        $imageFile->ThumbnailSize = $GLOBALS['ThumbnailSize'];
        $imageFile->LinkerType='category';
        if ($_FILES ['categoryimage'] ['error'] == 0) {
            $imageFile->DeleteImageOnLinker($categoryid);
            $file ['categoryimage'] ['name'] = $_FILES ['categoryimage'] ['name'];
            $file ['categoryimage'] ['type'] = $_FILES ['categoryimage'] ['type'];
            $file ['categoryimage'] ['tmp_name'] = $_FILES ['categoryimage'] ['tmp_name'];
            $file ['categoryimage'] ['error'] = $_FILES ['categoryimage'] ['error'];
            $file ['categoryimage'] ['size'] = $_FILES ['categoryimage'] ['size'];
            $file ['categoryimage'] ['isdefault'] = 1;
            $file ['categoryimage'] ['caption'] = '';
            $file ['categoryimage'] ['tag'] = $categoryid;
            $file ['categoryimage'] ['status'] = 1;

            $filename = $imageFile->Upload ( $file, true, $categoryid);
            $imageuploaderror = $imageFile->LastErrors ();
            if (count ( $imageuploaderror ) > 0) {
                foreach ( $imageuploaderror as $i => $v ) {
                    $messages [] = array (
                        "message" => $v,
                        "level" => 10
                    );
                }
            }
        }
    }
    // Code return for update image in category fields
    
    if ($isupdated) {
        $messages [] = array (
            "message" => _ ( "Category has been updated successfully." ),
            "level" => 1
        );
    } else {
        if (count(Category::GetErrors()) > 0) {
            $a = Category::GetErrors();
            foreach ($a as $i => $v) {
                $messages[] = $v;
            }
        }
        else
        {
            $messages[] = array(
                "message" => _("Unable to update category at this moment. Please try again later."),
                "level" => 10
            );
        }
    }
}

$pageParse ['FooterInclusion'] = '<script>
    $(function(){
        if ($(".showimage").length >0) {
        	$(".showimage").each(function(i, v) {
        		var ImageID = $(this).data("imageid");
        		var imageresize = HomeURL + "/resizeimage.php?id=" + ImageID + "&w=550&h=415";
        		$(this).data("href", imageresize).addClass("popup").prop("href", imageresize);
        	});
        }
        $(".deleteimage").click(function(e){
            e.preventDefault();
            var imageid = $(this).data("imageid");
            $.post(HomeURL+"/deleteimage.php",
                {delete: imageid},
                function(data){
                    if(data == 1){
                        $(".deleteimage").parent().remove();
                    } else{
                        alert("Unable to delete the file");
                    }
                }
            );
        });
        $(".showimage").colorbox({iframe: true, width: "80%", height: "80%"});
    });
    
    </script>';
$pageParse ['Content'] = DisplayForm ( $categoryid );
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");
