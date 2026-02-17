# project_management
案件管理システム

# プロジェクト管理システム
## システム概要

デザイナー、PM、開発者が在籍する制作会社向けの、案件管理・タスク管理・工数記録を一元化するシステム。
各メンバーの稼働状況を可視化し、プロジェクトの進捗をリアルタイムで把握できることを目指す。

## 主要機能

### 1. 案件管理機能
- 案件の登録・編集・削除
- 案件ステータス管理（提案中、進行中、完了、保留など）
- クライアント情報の紐付け
- 予算・見積金額の管理
- 納期設定

### 2. タスク管理機能
- 案件ごとのタスク作成
- タスクの担当者アサイン（複数人可）
- 優先度設定（高・中・低）
- ステータス管理（未着手、進行中、レビュー待ち、完了）
- 期限設定とアラート機能
- タスク間の依存関係設定

### 3. 工数記録機能
- タスクに対する作業時間の記録
- 日次・週次での工数入力
- タイマー機能（作業開始/終了ボタン）
- 実績工数と予定工数の比較
- コメント・作業内容メモ

### 4. ダッシュボード機能
- 案件別進捗状況の可視化
- メンバー別稼働状況グラフ
- 期限が迫っているタスク一覧
- 今週/今月の工数サマリー
- プロジェクト別の予算消化率

### 5. レポート機能
- 月次工数レポート（メンバー別・案件別）
- 案件別収支レポート
- 稼働率分析
- CSV/PDFエクスポート

### 6. ユーザー管理機能
- 役割別権限設定（管理者、PM、メンバー）
- プロフィール管理
- 通知設定

## 技術スタック

### バックエンド
- Laravel 11.x
- PHP 8.2以上
- MySQL 8.0
- Laravel Sanctum（認証）
- Laravel Queue（非同期処理）

### フロントエンド
- Blade Template
- Alpine.js（軽量なインタラクティブ機能）
- Tailwind CSS
- Chart.js（グラフ表示）

### 開発環境
- Docker / Laravel Sail
- Git / GitHub

## データベース設計（詳細定義）

### users（ユーザー）
```sql
id: bigint unsigned, primary key, auto_increment
name: varchar(255), not null
email: varchar(255), not null, unique
password: varchar(255), not null
role: enum('admin', 'pm', 'member'), not null, default 'member'
hourly_rate: decimal(8,2), nullable, comment '時間単価（円）'
avatar: varchar(255), nullable
is_active: boolean, default true
created_at: timestamp
updated_at: timestamp
```

### clients（クライアント）
```sql
id: bigint unsigned, primary key, auto_increment
name: varchar(255), not null, comment '担当者名'
company_name: varchar(255), not null, index
email: varchar(255), nullable
phone: varchar(20), nullable
address: text, nullable
notes: text, nullable
is_active: boolean, default true
created_at: timestamp
updated_at: timestamp
```

### projects（案件）
```sql
id: bigint unsigned, primary key, auto_increment
client_id: bigint unsigned, not null, foreign key -> clients.id
name: varchar(255), not null, index
code: varchar(50), nullable, unique, comment '案件コード（例：PRJ-2024-001）'
description: text, nullable
status: enum('proposal', 'in_progress', 'on_hold', 'completed', 'cancelled'), not null, default 'proposal'
budget: decimal(12,2), nullable, comment '予算（円）'
estimated_hours: decimal(8,2), nullable, comment '見積工数（時間）'
start_date: date, nullable
end_date: date, nullable
created_by: bigint unsigned, not null, foreign key -> users.id
created_at: timestamp
updated_at: timestamp
deleted_at: timestamp, nullable, comment 'ソフトデリート用'

index: (status, end_date), (client_id, status)
```

### tasks（タスク）
```sql
id: bigint unsigned, primary key, auto_increment
project_id: bigint unsigned, not null, foreign key -> projects.id, on delete cascade
title: varchar(255), not null
description: text, nullable
status: enum('not_started', 'in_progress', 'in_review', 'completed', 'on_hold'), not null, default 'not_started'
priority: enum('low', 'medium', 'high'), not null, default 'medium'
estimated_hours: decimal(8,2), nullable, comment '見積工数（時間）'
start_date: date, nullable
due_date: date, nullable
completed_at: timestamp, nullable
created_by: bigint unsigned, not null, foreign key -> users.id
sort_order: integer, default 0, comment '表示順'
created_at: timestamp
updated_at: timestamp
deleted_at: timestamp, nullable

index: (project_id, status), (due_date), (status, priority)
```

### task_assignments（タスク担当者）
```sql
id: bigint unsigned, primary key, auto_increment
task_id: bigint unsigned, not null, foreign key -> tasks.id, on delete cascade
user_id: bigint unsigned, not null, foreign key -> users.id, on delete cascade
created_at: timestamp
updated_at: timestamp

unique: (task_id, user_id)
index: (user_id, task_id)
```

### time_entries（工数記録）
```sql
id: bigint unsigned, primary key, auto_increment
task_id: bigint unsigned, not null, foreign key -> tasks.id, on delete cascade
user_id: bigint unsigned, not null, foreign key -> users.id
hours: decimal(5,2), not null, comment '作業時間（時間）', check (hours > 0 AND hours <= 24)
work_date: date, not null, index
description: text, nullable
started_at: timestamp, nullable, comment 'タイマー開始時刻'
ended_at: timestamp, nullable, comment 'タイマー終了時刻'
created_at: timestamp
updated_at: timestamp

index: (user_id, work_date), (task_id, work_date)
unique: (task_id, user_id, work_date) - 同じ日に同じタスクに複数記録不可
```

## 画面構成

### 認証画面
- ログイン
- パスワードリセット

### ダッシュボード
- サマリー表示（担当タスク、今週の工数、アラートなど）

### 案件管理
- 案件一覧
- 案件詳細（タスク一覧、工数サマリー含む）
- 案件登録・編集

### タスク管理
- タスク一覧（フィルタ・検索機能）
- タスク詳細（工数記録含む）
- タスク登録・編集

### 工数記録
- 日次工数入力画面
- タイマー機能付き記録画面
- 工数一覧・編集

### レポート
- 月次レポート
- 案件別レポート
- メンバー別レポート

### 設定
- ユーザー管理
- クライアント管理
- プロフィール設定

## 非機能要件

- レスポンシブデザイン（PC・タブレット対応）
- データバックアップ機能
- セキュリティ対策（CSRF、XSS、SQLインジェクション対策）
- パフォーマンス：ページ読み込み3秒以内

## 開発タスク（予定）

### Phase 1: 環境構築・基本機能（1-2週目）
- [ ] Laravel 11プロジェクト作成
- [ ] Docker環境構築（Laravel Sail）
- [ ] データベース設計・マイグレーション作成
- [ ] 認証機能実装（Laravel Breeze/Sanctum）
- [ ] ユーザー管理機能（CRUD）
- [ ] 基本レイアウト作成（Tailwind CSS）

### Phase 2: コア機能実装（3-4週目）
- [ ] クライアント管理機能（CRUD）
- [ ] 案件管理機能（CRUD）
- [ ] 案件一覧・詳細画面
- [ ] タスク管理機能（CRUD）
- [ ] タスク一覧・詳細画面
- [ ] 担当者アサイン機能

### Phase 3: 工数記録機能（5-6週目）
- [ ] 工数記録機能（CRUD）
- [ ] タイマー機能実装
- [ ] 日次工数入力画面
- [ ] 工数一覧・編集機能
- [ ] 工数集計ロジック

### Phase 4: ダッシュボード・可視化（7-8週目）
- [ ] ダッシュボード画面作成
- [ ] 案件別進捗グラフ（Chart.js）
- [ ] メンバー別稼働グラフ
- [ ] 期限アラート機能
- [ ] 通知機能（メール/画面内通知）

### Phase 5: レポート機能（9-10週目）
- [ ] 月次レポート画面
- [ ] 案件別レポート
- [ ] メンバー別レポート
- [ ] CSV/PDFエクスポート機能
- [ ] フィルタ・検索機能の強化

### Phase 6: 仕上げ・テスト（11-12週目）
- [ ] 権限管理機能の実装
- [ ] バリデーション強化
- [ ] エラーハンドリング改善
- [ ] レスポンシブ対応確認
- [ ] セキュリティチェック
- [ ] 単体テスト作成
- [ ] 統合テスト実施
- [ ] パフォーマンスチューニング
- [ ] ドキュメント整備

### 拡張機能（余裕があれば）
- [ ] ガントチャート表示
- [ ] カレンダービュー
- [ ] APIエンドポイント作成
- [ ] Slack連携
- [ ] ファイル添付機能
- [ ] コメント機能
- [ ] タスクテンプレート機能

---

## AI開発時の重要な制約・前提条件

### 必ず守るべきLaravel規約
1. **命名規則**
   - モデル: 単数形、PascalCase（例: `Project`, `TimeEntry`）
   - コントローラー: 単数形 + Controller（例: `ProjectController`）
   - テーブル: 複数形、snake_case（例: `projects`, `time_entries`）
   - マイグレーションファイル: `YYYY_MM_DD_HHMMSS_create_projects_table.php`
   
2. **ディレクトリ構造**
   ```
   app/
   ├── Http/
   │   ├── Controllers/
   │   ├── Requests/        # FormRequest クラス
   │   └── Middleware/
   ├── Models/
   ├── Policies/
   └── Services/            # ビジネスロジック
   
   database/
   ├── migrations/
   ├── seeders/
   └── factories/
   
   resources/
   ├── views/
   │   ├── layouts/
   │   ├── components/
   │   ├── projects/
   │   ├── tasks/
   │   └── time-entries/
   └── js/
   
   routes/
   ├── web.php
   └── api.php
   
   tests/
   ├── Feature/
   └── Unit/
   ```

3. **必須の関連実装**
   - モデル作成時は必ず対応する Migration, Factory, Seeder も作成
   - コントローラー作成時は必ず対応する FormRequest, Policy も作成
   - リレーション定義は必ず双方向（hasMany ⇔ belongsTo）

### コーディング規約

1. **Eloquent の使い方**
   ```php
   // ✅ Good: Eager Loading
   $projects = Project::with(['client', 'tasks.assignments.user'])->paginate(20);
   
   // ❌ Bad: N+1 クエリ
   $projects = Project::all();
   foreach ($projects as $project) {
       echo $project->client->name; // N+1 発生
   }
   ```

2. **バリデーション**
   ```php
   // ✅ Good: FormRequest 使用
   public function store(StoreProjectRequest $request) {
       $project = Project::create($request->validated());
   }
   
   // ❌ Bad: コントローラーに直接記述
   public function store(Request $request) {
       $request->validate([...]); // FormRequest に分離すべき
   }
   ```

3. **認可チェック**
   ```php
   // ✅ Good: Policy 使用
   $this->authorize('update', $project);
   
   // ❌ Bad: 直接ロールチェック
   if (auth()->user()->role !== 'admin') { abort(403); }
   ```

4. **トランザクション**
   ```php
   // ✅ Good: DB トランザクション
   DB::transaction(function () use ($request) {
       $project = Project::create($request->validated());
       $project->tasks()->createMany($request->tasks);
   });
   ```

### 禁止事項（必ず避けるべきパターン）

1. **生 SQL の使用禁止**
   ```php
   // ❌ 絶対に禁止
   DB::select("SELECT * FROM projects WHERE id = " . $id);
   
   // ✅ Eloquent/Query Builder 使用
   Project::find($id);
   ```

2. **マスアサインメント脆弱性**
   ```php
   // ❌ Bad: $fillable/$guarded 未設定
   $project = Project::create($request->all());
   
   // ✅ Good: FormRequest + $fillable 設定
   // Model に protected $fillable = ['name', 'client_id', ...];
   $project = Project::create($request->validated());
   ```

3. **パスワード平文保存禁止**
   ```php
   // ❌ 絶対に禁止
   $user->password = $request->password;
   
   // ✅ Hash 使用
   $user->password = Hash::make($request->password);
   ```

4. **コントローラーに複雑なロジック記述禁止**
   ```php
   // ❌ Bad: コントローラーが肥大化
   public function store(Request $request) {
       // 100行以上のビジネスロジック...
   }
   
   // ✅ Good: Service クラスに分離
   public function store(StoreProjectRequest $request, ProjectService $service) {
       $project = $service->createProject($request->validated());
       return redirect()->route('projects.show', $project);
   }
   ```

### テストの必須要件

各機能実装時に最低限必要なテスト:

```php
// Feature Test 例
tests/Feature/ProjectTest.php
- test_user_can_view_project_list()
- test_user_can_create_project()
- test_user_cannot_create_project_without_permission()
- test_user_can_update_own_project()
- test_user_can_delete_project()
- test_validation_fails_with_invalid_data()

// Unit Test 例
tests/Unit/ProjectTest.php
- test_project_belongs_to_client()
- test_project_has_many_tasks()
- test_calculate_total_hours()
```

### エラーハンドリング

```php
// ✅ 適切な例外処理
try {
    DB::transaction(function () use ($request) {
        // 処理
    });
} catch (\Exception $e) {
    Log::error('Project creation failed: ' . $e->getMessage());
    return back()->withErrors(['error' => '案件の作成に失敗しました。']);
}

// ✅ 404 の適切な処理
$project = Project::findOrFail($id); // 存在しない場合は自動で 404

// ✅ 権限エラーの処理
$this->authorize('update', $project); // 権限なしは自動で 403
```

### パフォーマンス最適化の必須対応

1. **ページネーション必須**
   ```php
   // ✅ 必ず paginate 使用
   $projects = Project::paginate(20);
   
   // ❌ 大量データで all() 禁止
   $projects = Project::all();
   ```

2. **Select 句の最適化**
   ```php
   // ✅ 必要なカラムのみ取得
   Project::select('id', 'name', 'status')->get();
   
   // ❌ 不要なカラムも取得
   Project::all(); // すべてのカラム取得
   ```

3. **キャッシュ活用（Phase 6 以降）**
   ```php
   // 頻繁にアクセスされるデータはキャッシュ
   $stats = Cache::remember('dashboard.stats', 3600, function () {
       return DB::table('projects')->selectRaw('status, count(*) as count')
           ->groupBy('status')->get();
   });
   ```

### Git コミットメッセージ規約

```
feat: 新機能追加
fix: バグ修正
docs: ドキュメント変更
style: コードスタイル修正（動作に影響なし）
refactor: リファクタリング
test: テスト追加・修正
chore: ビルドプロセス、補助ツール変更

例:
feat: プロジェクト一覧画面の実装
fix: 工数記録の保存時にエラーが発生する問題を修正
docs: README にセットアップ手順を追加
```

### AI に実装を依頼する際のテンプレート

```
以下の機能を実装してください:

【機能名】
案件一覧画面

【要件】
- URL: /projects
- 表示項目: 案件コード、案件名、クライアント名、ステータス、予算、進捗率、納期
- フィルタ: ステータス、クライアント
- ページネーション: 20件/ページ
- 権限: 全ユーザーが閲覧可能

【実装ファイル】
1. Migration: YYYY_MM_DD_HHMMSS_create_projects_table.php
2. Model: app/Models/Project.php
3. Controller: app/Http/Controllers/ProjectController.php
4. FormRequest: app/Http/Requests/StoreProjectRequest.php
5. Policy: app/Policies/ProjectPolicy.php
6. View: resources/views/projects/index.blade.php
7. Route: routes/web.php
8. Test: tests/Feature/ProjectTest.php

【注意事項】
- N+1 クエリ対策として Eager Loading 必須
- Policy による権限チェック必須
- バリデーションは FormRequest に記述
- エラーハンドリングを適切に実装
```

---

## 学習ポイント

このプロジェクトを通じて以下のLaravelスキルを習得できます：

1. **基礎**
   - MVC アーキテクチャ
   - Eloquent ORM（リレーション、スコープ）
   - マイグレーション・シーダー

2. **中級**
   - 認証・認可（Policy、Gate）
   - フォームリクエスト・バリデーション
   - リソースコントローラー
   - ミドルウェア

3. **応用**
   - イベント・リスナー
   - ジョブ・キュー
   - 通知機能
   - テスト駆動開発（TDD）

## 参考リソース

- [Laravel 公式ドキュメント](https://laravel.com/docs)
- [Laracasts](https://laracasts.com/)
- [Laravel Daily](https://laraveldaily.com/)

---

## 開発進め方

### 勉強会形式での進行案
1. **週次ミーティング**：進捗確認、技術的な疑問点の共有
2. **ペアプログラミング**：難しい機能は複数人で実装
3. **コードレビュー**：Pull Request ベースで相互レビュー
4. **ふりかえり**：各Phaseごとに学んだことを共有

### Git運用
- main ブランチ：本番相当
- develop ブランチ：開発統合
- feature/機能名 ブランチ：機能開発