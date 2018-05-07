<?php

class Friend extends User
{
    /**
     * the current user id
     *
     * @var int
     */
    private $userId;

    /**
     * assign thecurrent user id
     *
     * @param int $id
     */
    function __construct($id)
    {
        $this->userId = $id;
    }

    /**
     * Search for friends using nickname, email or first|last name
     * using userId and get the relation between the user and them
     *
     * @return array
     */
    public function searchForFriends($search)
    {
        $search = "%$search%";

        $params = [
            ':me1'      => $this->userId,
            ':me2'      => $this->userId,
            ':me3'      => $this->userId,
            ':search1'  => $search,
            ':search2'  => $search,
            ':search3'  => $search,
            ':search4'  => $search
        ];

        $db = DB::getInstance();
        $db->select('accounts.*, requests.*
                        FROM
                            accounts
                        LEFT JOIN
                            requests
                          ON
                            (accounts.id = send_id AND received_id = :me1)
                                OR
                            (accounts.id = received_id AND send_id = :me2)
                        WHERE
                            accounts.id != :me3
                        AND (
                            first_name LIKE :search1
                                OR
                            last_name LIKE :search2
                                OR
                            nick_name LIKE :search3
                                OR
                            email LIKE :search4)', $params);

        return $db->results();
    }

    /**
     * Get all friends of the userId
     *
     * @return array
     */
    public function getAllFriends()
    {
        $db = DB::getInstance();

        $select1 = 'user.id, requests.*, friends.*
                        FROM
                            accounts AS user
                        INNER JOIN
                            requests
                        ON
                            user.id = send_id
                        INNER JOIN
                            accounts AS friends
                        ON
                            friends.id = received_id';


        $select2 = 'user.id, requests.*, friends.*
                        FROM
                            accounts AS user
                        INNER JOIN
                            requests
                        ON
                            user.id = received_id
                        INNER JOIN
                            accounts AS friends
                        ON
                            friends.id = send_id';

        $where = 'user.id = :account_id
                        AND
                    status = 2';


        return $db->union($select1, $select2, $where, [':account_id' => $this->userId]);
    }

    /**
     * Get all friend requests
     *
     * @return bool
     */
    public function getAllfriendRequests()
    {
        $db = DB::getInstance();

        $sql = 'user.id, requests.*, friends.*
                    FROM
                        accounts AS user
                    INNER JOIN
                        requests
                      ON
                        user.id = received_id
                    INNER JOIN
                        accounts AS friends
                      ON
                        friends.id = send_id
                    WHERE
                        user.id = :account_id
                      AND
                        status = 1';

        $db->select($sql, [
            ':account_id' => $this->userId
        ]);

        return $db->results();
    }

    /**
     * Send a friend request from the userId to the given $received_id
     *
     * @param int $received_id
     * @return bool
     */
    public function sendRequest($received_id)
    {
        $db = DB::getInstance();

        $sql = 'INTO
                    requests
                SET
                    received_id = :received_id,
                    send_id     = :send_id,
                    sent_at     = :sent_at,
                    status      = 1';

        $db->insert($sql, [
            ':received_id'  => $received_id,
            ':send_id'      => $this->userId,
            ':sent_at'      => date('Y-m-d H:i:s')
        ]);

        return $db->errors();
    }

    /**
     * Accept a friend request from the given $send_id
     *
     * @param int $send_id
     * @return bool
     */
    public function acceptRequest($send_id)
    {
        $db = DB::getInstance();

        $sql = 'requests
                    SET
                        status = 2
                    WHERE
                        received_id = :received_id
                      AND
                        send_id = :send_id';

        $db->update($sql, [
            ':received_id'  => $this->userId,
            ':send_id'      => $send_id
        ]);

        return $db->errors();
    }

    /**
     * reject a friend request from the given $send_id
     *
     * @param int $send_id
     * @return bool
     */
    public function rejectRequest($send_id)
    {
        $db = DB::getInstance();

        $sql = 'FROM
                    requests
                WHERE
                    received_id = :received_id
                  AND
                    send_id = :send_id';

        $db->delete($sql, [
            ':received_id'  => $this->userId,
            ':send_id'      => $send_id
        ]);

        return $db->errors();
    }

    /**
     * delete the friendship between the current userId and the given friendId
     *
     * @param  int $friendId
     * @return bool
     */
    public function unfriend($friendId)
    {
        $db = DB::getInstance();

        $sql = 'FROM
                    requests
                WHERE
                    (send_id = :me1 AND received_id = :friend1)
                  OR
                    (send_id = :friend2 AND received_id = :me2)
                    ';

        $db->delete($sql, [
            'me1'       => $this->userId,
            'friend1'   => $friendId,
            'me2'       => $this->userId,
            'friend2'   => $friendId
        ]);

        return $db->errors();
    }

}
