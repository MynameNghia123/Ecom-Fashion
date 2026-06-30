<?php
namespace App\Enums;

enum StatusEnum: int
{
    case PENDING = 0;   // Đang chờ
    case APPROVED = 1;  // Đã duyệt
    case CANCEL = 2;    // Bị từ chối
    case COMPLETED = 3; // đã hoàn thành
}