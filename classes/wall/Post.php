<?php


class Post
{

    /**
     * Container of the post data
     *
     * @var array
     */
    private $data;

    /**
     * Path of the post's image
     *
     * @var string
     */
    private $imagePath = PATH . 'uploads' . DS . 'posts' . DS;

    /**
     * fill the data attribute
     *
     * @param array $data
     */
    function __construct($data = null)
    {
        $this->data = $data;
    }

    /**
     * create new post using data container
     *
     * @return bool
     */
    public function createPost()
    {
        $image = new UploadedFile('image');

        if (!$this->postValidator($image)) {
            return false;
        }

        $params = [
            ':caption'    => $this->data['caption'],
            ':status'     => $this->data['status'],
            ':created_at' => date('Y-m-d H:i:s'),
            ':account_id' => $_SESSION['user'],
        ];

        $imgeField = '';
        if ($image->isExists()) {
            $imgeField = 'image       = :image,';
            $params[':image'] = $image->getNewFileName();
            $image->moveTo($this->imagePath);
        }


        $db = DB::getInstance();


        // insert [caption, image, status, created_at, account_id] into the database
        $db->insert("INTO posts SET
                        caption     = :caption,
                        $imageField
                        status      = :status,
                        created_at  = :created_at,
                        account_id  = :account_id",
                    $params);

        if ($db->errors()) {
            $_SESSION['errors'][] = 'can\'t insert';
            return false;
        }
        return true;
    }

    /**
     * Get all the posts of the current user's friends
     *
     * @return array
     */
     public static function getWallPosts()
     {
         $db = DB::getInstance();

         # the most absurd query I've ever made
         $sql = 'posts.*, accounts.first_name, accounts.last_name, accounts.nick_name, accounts.picture, accounts.gender
                  FROM posts
                    INNER JOIN (
                        SELECT t1.send_id AS friends
                            FROM (
                              SELECT requests.send_id, requests.received_id
                                FROM
                                  requests
                                WHERE
                                  requests.status = 2
                                    AND (
                                  requests.send_id = :me1
                                    OR
                                  requests.received_id = :me2
                                  )
                            ) AS t1
                        UNION
                          SELECT t1.received_id AS friends
                            FROM (
                              SELECT requests.send_id, requests.received_id
                                FROM
                                  requests
                                WHERE
                                  requests.status = 2
                                    AND (
                                  requests.send_id = :me3
                                    OR
                                  requests.received_id = :me4
                                  )
                            ) AS t1
                        UNION
                          SELECT :me5
                    ) AS t2
                      ON t2.friends = posts.account_id
                    INNER JOIN
                      accounts
                        ON posts.account_id = accounts.id
                    ORDER BY
                      created_at DESC';


         $db->select($sql, [
             'me1' => $_SESSION['user'],
             'me2' => $_SESSION['user'],
             'me3' => $_SESSION['user'],
             'me4' => $_SESSION['user'],
             'me5' => $_SESSION['user']
         ]);

         return $db->results();
     }

    /**
     * get all posts for a specific profile
     *
     * @param $id
     * @return array
     */
    public static function getAccountPosts($id)
    {
        $db = DB::getInstance();

        $sql = 'posts.*, accounts.nick_name, accounts.first_name, accounts.last_name, accounts.picture, accounts.gender
                    FROM
                        posts
                    INNER JOIN
                        accounts
                      ON
                        posts.account_id = accounts.id
                    WHERE
                        accounts.id = :account_id
                    ORDER BY
                      created_at DESC';

        $db->select($sql, [
            ':account_id' => $id
        ]);

        return $db->results();
    }

    /**
     * Create a private post when user change his profile picture
     *
     * @return bool
     */
    public function createProfilePicturePost()
    {
        $db = DB::getInstance();

        $picture = basename($this->data['picturePath']);

        $params = [
            ':caption'    => $this->data['caption'],
            ':image'      => $picture,
            ':status'     => 2,
            ':created_at' => date('Y-m-d H:i:s'),
            ':account_id' => $_SESSION['user']
        ];

        copy($this->data['picturePath'], "{$this->imagePath}{$picture}");

        // insert [caption, image, status, created_at, account_id] into the database
        $db->insert('INTO posts SET
                        caption     = :caption,
                        image       = :image,
                        status      = :status,
                        created_at  = :created_at,
                        account_id  = :account_id',
                    $params);

        if ($db->errors()) {
            $_SESSION['errors'][] = 'can\'t insert';
            return false;
        }

        return true;
    }

    /**
     * Given a post object it will return the status of the post and a class
     * primary for public 3 - warning for private 2 - danger for only me 1
     *
     * @param  stdClass $post
     * @return array
     */
    public function getPostStatus($post)
    {
        switch ($post->status) {
            case 1:
                $status = 'Only me';
                $statusClass = 'danger';
                break;

            case 2:
                $status = 'private';
                $statusClass = 'primary';
                break;

            case 3:
                $status = 'public';
                $statusClass = 'info';
                break;
        }
        return [$status, $statusClass];
    }


    /**
     * validating the [caption, user, image, status]
     * for the new post form
     *
     * @return bool
     */
    private function postValidator($image)
    {
        $validator = new Validator($this->data);

        if ($image->isExists()) {
            $validator->image('image');
        } else {
            $validator->required('caption');
        }

        if ($validator->fails()) {
            $_SESSION['errors'] = $validator->getMessages();
            return false;
        }
        return true;
    }

}
