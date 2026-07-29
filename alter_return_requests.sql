-- Thêm các cột thiếu vào return_requests nếu chưa có
ALTER TABLE return_requests 
  ADD COLUMN IF NOT EXISTS ticket_code VARCHAR(50) UNIQUE AFTER id,
  ADD COLUMN IF NOT EXISTS order_detail_id BIGINT UNSIGNED NULL AFTER order_id,
  ADD COLUMN IF NOT EXISTS customer_note TEXT NULL AFTER reason,
  ADD COLUMN IF NOT EXISTS quantity INT NOT NULL DEFAULT 1 AFTER customer_note,
  ADD COLUMN IF NOT EXISTS admin_note TEXT NULL AFTER status,
  ADD COLUMN IF NOT EXISTS processed_at TIMESTAMP NULL AFTER admin_note;

-- Cập nhật ticket_code cho các record cũ chưa có
UPDATE return_requests SET ticket_code = CONCAT('#RET-', LPAD(id, 4, '0')) WHERE ticket_code IS NULL;
