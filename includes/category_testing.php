<div class="sub-sub-category-slider-outer ">
                    <div class="sub-sub-category-slider">
                        <?php
                        if (isset($_SESSION['cat_child_id'])) {
                            $cat_child_id = $_SESSION['cat_child_id'];

                            $get_senior_cat =  $db->select("categories_children", array("child_id" => $cat_child_id));
                            $row_senior_cat = $get_senior_cat->fetch();
                            $child_url = $row_senior_cat->child_url;
                            $child_parent_id = $row_senior_cat->child_parent_id;

                            $get_parent_cat =  $db->select("categories", array("cat_id" => $child_parent_id));
                            $row_parent_cat = $get_parent_cat->fetch();
                            $cat_url = $row_parent_cat->cat_url;

                            $get_sub_sub_cat = $db->select("cat_attribute", array("child_id" => $cat_child_id, "language_id" => $siteLanguage));
                            while ($row_sub_sub_cat = $get_sub_sub_cat->fetch()) {
                                $attr_id = $row_sub_sub_cat->attr_id;
                                $cat_attr = $row_sub_sub_cat->cat_attr;

                                // Fetching sub-subcategory details
                                $get_sub_meta = $db->select("sub_subcategories", array("attr_id" => $attr_id, "language_id" => $siteLanguage));
                                while ($row_sub_meta = $get_sub_meta->fetch()) {
                                    $sub_subcategory_name = $row_sub_meta->sub_subcategory_name;
                                    $image = $row_sub_meta->image;
                                    $image_path = $site_url . "/uploads/" . $image; // Adjust this path according to your setup
                        ?>
                                    <div class="sub-sub-category-tablet">

                                        <a class="nav-link text-info d-flex p-0 <?php if ($attr_id == @$_SESSION['attr_id']) {
                                                                                    echo "active";
                                                                                } ?>" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>/<?= $child_url; ?>/<?= $cat_attr; ?>">
                                            <div class="icon-sub-sub-category"><img class="image-style-icon" src="<?= $image_path; ?>" alt="img"></div>
                                            <div class="sub_subcategory_name_showing"><?= $sub_subcategory_name; ?> </div>
                                        </a>
                                    </div>
                        <?php
                                }
                            }
                        }
                        ?>
                    </div>
                    <button class="prev-slide slide-button" onclick="moveSlide(-1)">&#10094;</button>
                    <button class="next-slide slide-button" onclick="moveSlide(1)">&#10095;</button>
                </div>

                <style>
                    .sub-sub-category-slider-outer {
                        position: relative;
                        width: 100%;
                        overflow: hidden;
                    }

                    .sub-sub-category-slider {
                        display: flex;
                        transition: transform 0.5s ease-in-out;
                    }

                    .sub-sub-category-tablet {
                        border: 1px solid #d3d3d3a8;
                        margin: auto 1rem;
                        padding: 0.5rem;
                        border-radius: 50px;
                        flex: 0 0 auto;
                        /* Prevent shrinking or expanding */
                    }

                    .sub-sub-category-tablet:hover {
                        border: 1px solid grey;
                    }

                    .icon-sub-sub-category {
                        background-color: #d3d3d342;
                        width: 3.5rem;
                        height: 3.5rem;
                        /* border: 1px solid lightgrey; */
                        border-radius: 50px;
                        margin-right: 1rem;
                        display: flex;
                    }

                    .image-style-icon {
                        width: 75%;
                        margin: auto;
                        border-radius: 10px;
                    }

                    .sub_subcategory_name_showing {
                        padding-right: 1rem;
                        align-content: center;
                    }

                    .slide-button {
                        position: absolute;
                        top: 50%;
                        transform: translateY(-50%);
                        background-color: rgba(0, 0, 0, 0.5);
                        color: white;
                        border: none;
                        padding: 10px;
                        cursor: pointer;
                    }

                    .prev-slide {
                        left: 10px;
                    }

                    .next-slide {
                        right: 10px;
                    }

                    /* skills */
                    .skills_name_showing {
                        border: 1px solid #d3d3d3a8;
                        width: auto;
                        /* background-color: #d3d3d330; */
                        padding: 1rem 2rem;
                        border-radius: 50px;
                        height: 4rem;
                        align-content: center;
                    }

                    .skills_name_showing:hover {
                        background-color: #d3d3d3a8;
                    }
                </style>
                <script>
                    let currentIndex = 0;

                    function showSlide(index) {
                        const slides = document.querySelector('.sub-sub-category-slider');
                        const totalSlides = slides.children.length;
                        if (index >= totalSlides) {
                            currentIndex = 0;
                        } else if (index < 0) {
                            currentIndex = totalSlides - 1;
                        } else {
                            currentIndex = index;
                        }
                        slides.style.transform = `translateX(-${currentIndex * 50 / totalSlides}%)`;
                    }

                    function moveSlide(step) {
                        showSlide(currentIndex + step);
                    }

                    // Initialize the slider
                    showSlide(currentIndex);
                </script>