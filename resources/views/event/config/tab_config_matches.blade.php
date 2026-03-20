<div class="mt-3">
    <div class="mb-3 d-flex justify-content-end">
        <button id="save-btn" class="btn btn-success me-2 d-none">
            <i class="la la-save me-1"></i>Save
        </button>
        <button id="cancel-btn" class="btn btn-secondary me-2 d-none">
            <i class="la la-undo me-1"></i>Cancel
        </button>
        <button id="generate-btn" class="btn btn-primary">
            <i class="la la-cogs me-1"></i>Generate
        </button>
    </div>

    <div class="row">
        <div class="col-md-4">
            <h5 class="d-flex align-items-center">
                <i class="la la-list me-2"></i>Bracket Pool
                <span id="pool-count" class="badge bg-secondary ms-2">0</span>
            </h5>
            <div id="bracket-pool" class="dropzone list-group bracket-pool-container"></div>
        </div>
        <div class="col-md-8 mat-area" id="schedule-container"></div>
    </div>
</div>

<style>
    /* ── Bracket Cards ── */
    .tree-node {
        cursor: grab;
        transition: transform 0.15s ease, box-shadow 0.15s ease, opacity 0.15s ease;
        user-select: none;
        background: #fff;
        border: 1px solid #e2e8f0 !important;
        border-left: 3px solid #6366f1 !important;
        border-radius: 6px !important;
        padding: 8px 12px !important;
        margin-bottom: 6px !important;
        font-size: 0.82rem;
    }
    .tree-node:hover:not(.locked-bracket) {
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.15);
        transform: translateY(-1px);
        border-left-color: #4f46e5 !important;
    }
    .tree-node.dragging {
        opacity: 0.4;
        transform: scale(0.96);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }
    .tree-node .bracket-label {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }
    .tree-node .bracket-tag {
        display: inline-block;
        padding: 1px 6px;
        border-radius: 3px;
        font-size: 0.72rem;
        font-weight: 500;
        background: #f1f5f9;
        color: #475569;
        white-space: nowrap;
    }
    .tree-node .bracket-tag.tag-name   { background: #ede9fe; color: #6d28d9; }
    .tree-node .bracket-tag.tag-age    { background: #dbeafe; color: #2563eb; }
    .tree-node .bracket-tag.tag-belt   { background: #fef3c7; color: #b45309; }
    .tree-node .bracket-tag.tag-weight { background: #d1fae5; color: #059669; }
    .tree-node .bracket-tag.tag-total  { background: #fee2e2; color: #dc2626; font-weight: 700; }

    /* ── Locked Bracket ── */
    .locked-bracket {
        opacity: 0.6;
        cursor: not-allowed !important;
        background-color: #f8fafc !important;
        border-left-color: #94a3b8 !important;
    }
    .locked-bracket:hover {
        transform: none !important;
        box-shadow: none !important;
    }

    /* ── Drop Zones ── */
    .dropzone {
        min-height: 80px;
        border: 2px dashed #cbd5e1 !important;
        border-radius: 10px !important;
        transition: border-color 0.2s ease, background-color 0.2s ease, box-shadow 0.2s ease;
        position: relative;
        background: #fafbfc;
    }
    .dropzone.drag-over {
        border-color: #6366f1 !important;
        background-color: rgba(99, 102, 241, 0.04) !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    .dropzone.drag-over::after {
        content: 'Энд тавина уу';
        position: absolute;
        bottom: 8px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 0.72rem;
        color: #6366f1;
        font-weight: 600;
        pointer-events: none;
        background: rgba(255,255,255,0.9);
        padding: 2px 10px;
        border-radius: 10px;
    }

    /* ── Drop Zone: insert position indicator ── */
    .tree-node.drop-above {
        border-top: 2px solid #6366f1 !important;
        margin-top: -2px;
    }
    .tree-node.drop-below {
        border-bottom: 2px solid #6366f1 !important;
        margin-bottom: -2px;
    }

    /* ── Empty State ── */
    .dropzone-empty-hint {
        text-align: center;
        color: #94a3b8;
        padding: 20px 12px;
        font-size: 0.82rem;
        font-style: italic;
    }

    /* ── Mat Layout ── */
    .mat-slider-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
    }
    .mat-slider {
        display: flex;
        gap: 16px;
        padding-bottom: 8px;
    }
    .mat-col {
        min-width: 320px;
        flex: 0 0 auto;
    }
    .mate-poll {
        max-height: 450px;
        overflow-y: auto;
        scrollbar-width: thin;
    }

    /* ── Mat Header (sticky) ── */
    .mat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 4px;
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 10px;
        position: sticky;
        top: 0;
        background: #fafbfc;
        z-index: 2;
        gap: 8px;
    }
    .mat-header h6 {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.95rem;
        margin: 0;
        flex-shrink: 0;
    }
    .mat-header-right {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .mat-count {
        font-size: 0.72rem;
        color: #64748b;
        background: #f1f5f9;
        padding: 2px 8px;
        border-radius: 10px;
        font-weight: 600;
        white-space: nowrap;
    }
    .mat-clear-btn {
        border: none;
        background: none;
        color: #94a3b8;
        cursor: pointer;
        font-size: 0.78rem;
        padding: 2px 6px;
        border-radius: 4px;
        transition: all 0.15s ease;
        display: flex;
        align-items: center;
        gap: 3px;
        white-space: nowrap;
    }
    .mat-clear-btn:hover {
        background: #fee2e2;
        color: #dc2626;
    }
    .mat-clear-btn.d-none { display: none !important; }

    /* ── Pool Search ── */
    .pool-search-wrap {
        position: sticky;
        top: 0;
        z-index: 3;
        background: #fafbfc;
        padding-bottom: 8px;
        margin-bottom: 4px;
    }
    .pool-search {
        width: 100%;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 6px 10px 6px 30px;
        font-size: 0.8rem;
        outline: none;
        transition: border-color 0.15s;
        background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='%2394a3b8' viewBox='0 0 24 24'%3E%3Cpath d='M15.5 14h-.79l-.28-.27A6.47 6.47 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z'/%3E%3C/svg%3E") no-repeat 10px center;
    }
    .pool-search:focus {
        border-color: #6366f1;
    }
    .pool-search-clear {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        font-size: 1rem;
        line-height: 1;
        padding: 0 4px;
        display: none;
    }
    .pool-search-clear.visible { display: block; }

    /* ── Collapsible Pool Categories ── */
    .pool-category {
        font-weight: 600;
        font-size: 0.8rem;
        color: #64748b;
        padding: 6px 0 4px;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 6px;
        margin-top: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 4px;
        user-select: none;
    }
    .pool-category:hover { color: #4338ca; }
    .pool-category:first-child { margin-top: 0; }
    .pool-category .collapse-icon {
        transition: transform 0.2s ease;
        font-size: 0.7rem;
    }
    .pool-category.collapsed .collapse-icon {
        transform: rotate(-90deg);
    }
    .pool-group {
        margin-left: 8px;
        margin-bottom: 4px;
    }
    .pool-group.collapsed-content { display: none; }

    /* ── Bracket hidden by search ── */
    .search-hidden { display: none !important; }
    .pool-group-hidden { display: none !important; }

    /* ── Day Label ── */
    .day-label {
        font-weight: 700;
        font-size: 1.15rem;
        padding: 10px 0 6px;
        color: #1e293b;
        border-bottom: 2px solid #6366f1;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* ── Save/Cancel animation ── */
    .controls-visible {
        animation: fadeInSlide 0.25s ease;
    }
    @keyframes fadeInSlide {
        from { opacity: 0; transform: translateY(-4px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Bracket Pool ── */
    .bracket-pool-container {
        max-height: 70vh;
        overflow-y: auto;
        scrollbar-width: thin;
        background: #fafbfc;
        border: 2px dashed #cbd5e1 !important;
        border-radius: 10px !important;
        padding: 10px !important;
    }

    /* ── Loading overlay ── */
    .saving-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(255,255,255,0.75);
        backdrop-filter: blur(2px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }
</style>

<script>
$(document).ready(function () {
    'use strict';

    // ─── State ───────────────────────────────────────────
    const state = {
        bracketPool: [],
        schedule: [],
        originalSchedule: null,
        shortcutMap: {},         // bracketKey → bracket data
        matAssignments: {},      // "dayId_matId" → [bracketKey, ...]
        bracketLocations: {},    // bracketKey → "dayId_matId"
        lockedBrackets: new Set(),
        isDirty: false,
    };

    // ─── DOM References ──────────────────────────────────
    const $pool         = $('#bracket-pool');
    const $schedule     = $('#schedule-container');
    const $saveBtn      = $('#save-btn');
    const $cancelBtn    = $('#cancel-btn');
    const $generateBtn  = $('#generate-btn');
    const $poolCount    = $('#pool-count');

    // ─── Helpers ─────────────────────────────────────────
    function bracketKey(b) {
        return `${b.entry_id}_${b.entry_belt_id}_${b.entry_age_id}_${b.entry_weight_id}`;
    }

    function parseBracketKey(key) {
        const [entry_id, entry_belt_id, entry_age_id, entry_weight_id] = key.split('_').map(Number);
        return { entry_id, entry_belt_id, entry_age_id, entry_weight_id };
    }

    function extractBracketsFromPool(obj) {
        const result = {};
        function walk(node) {
            if (Array.isArray(node)) {
                node.forEach(item => {
                    const key = bracketKey(item);
                    if (!result[key]) result[key] = item;
                });
            } else if (node && typeof node === 'object') {
                Object.values(node).forEach(walk);
            }
        }
        walk(obj);
        return result;
    }

    // ─── Dirty State / Controls ──────────────────────────
    function markDirty() {
        if (!state.isDirty) {
            state.isDirty = true;
            $saveBtn.removeClass('d-none').addClass('controls-visible');
            $cancelBtn.removeClass('d-none').addClass('controls-visible');
        }
    }

    function markClean() {
        state.isDirty = false;
        $saveBtn.addClass('d-none').removeClass('controls-visible');
        $cancelBtn.addClass('d-none').removeClass('controls-visible');
    }

    // ─── Bracket Rendering ───────────────────────────────
    function createBracketNode(bracket) {
        const key = bracketKey(bracket);
        const isLocked = !!bracket.is_complete;

        if (isLocked) {
            state.lockedBrackets.add(key);
        }

        const node = document.createElement('div');
        node.className = 'tree-node';
        node.dataset.id = key;
        node.draggable = !isLocked;

        const name   = bracket?.entry?.fullname || '—';
        const age    = bracket?.age?.name || '—';
        const belt   = bracket?.belt?.name || '—';
        const weight = bracket?.weight?.weight || '—';
        const total  = bracket?.total ?? 0;

        const lockIcon = isLocked ? '<i class="la la-lock me-1 text-warning"></i>' : '';

        node.innerHTML = `
            <div class="bracket-label">
                ${lockIcon}
                <span class="bracket-tag tag-name">${name}</span>
                <span class="bracket-tag tag-age">${age}</span>
                <span class="bracket-tag tag-belt">${belt}</span>
                <span class="bracket-tag tag-weight">${weight}</span>
                <span class="bracket-tag tag-total">${total}</span>
            </div>
        `;

        if (isLocked) {
            node.classList.add('locked-bracket');
            node.title = 'Энэ хаалт эхэлсэн тул зөөх боломжгүй';
        }

        return node;
    }

    function renderBracketsInto(container, brackets) {
        brackets.forEach(bracket => {
            const key = bracketKey(bracket);
            // Skip if already rendered in this container
            if (container.querySelector(`[data-id="${key}"]`)) return;
            container.appendChild(createBracketNode(bracket));
        });
    }

    function renderPoolTree(container, treeData, depth) {
        depth = depth || 1;

        if (Array.isArray(treeData)) {
            renderBracketsInto(container, treeData);
            return;
        }

        Object.keys(treeData).forEach(key => {
            const node = treeData[key];
            const wrapper = document.createElement('div');
            wrapper.className = 'pool-group';

            const label = document.createElement('div');
            label.className = 'pool-category';
            label.textContent = key;
            wrapper.appendChild(label);

            if (node && typeof node === 'object') {
                const childContainer = document.createElement('div');
                childContainer.className = 'pool-group';
                renderPoolTree(childContainer, node, depth + 1);
                wrapper.appendChild(childContainer);
            }

            container.appendChild(wrapper);
        });
    }

    function updatePoolCount() {
        const assignedCount = Object.keys(state.bracketLocations).length;
        const totalBrackets = Object.keys(state.shortcutMap).length;
        const unassigned = totalBrackets - assignedCount;
        $poolCount.text(unassigned);
    }

    // ─── Clear Mat ───────────────────────────────────────
    function clearMat(matKey) {
        const brackets = state.matAssignments[matKey];
        if (!brackets || brackets.length === 0) return;

        // Only remove unlocked brackets
        const toRemove = brackets.filter(bKey => !state.lockedBrackets.has(bKey));
        if (toRemove.length === 0) return;

        toRemove.forEach(bKey => {
            // Remove DOM node
            const node = document.querySelector(`[data-id="${bKey}"]`);
            if (node) node.remove();

            // Remove from state
            const idx = state.matAssignments[matKey].indexOf(bKey);
            if (idx > -1) state.matAssignments[matKey].splice(idx, 1);
            delete state.bracketLocations[bKey];
        });

        // Show empty hint if mat is now empty
        const zone = document.querySelector(`.dropzone[data-key="${matKey}"]`);
        if (zone && state.matAssignments[matKey].length === 0) {
            if (!zone.querySelector('.dropzone-empty-hint')) {
                const hint = document.createElement('div');
                hint.className = 'dropzone-empty-hint';
                hint.textContent = 'Хаалтуудыг энд чирнэ үү';
                zone.appendChild(hint);
            }
        }

        // Re-render pool to show returned brackets
        $pool.empty();
        renderPoolTree($pool[0], state.bracketPool);
        setupPoolSearch();
        setupCategoryCollapse();
        setupDragAndDrop();

        markDirty();
        updateMatCounts();
        updatePoolCount();
    }

    // ─── Pool Search ─────────────────────────────────────
    function setupPoolSearch() {
        const poolEl = $pool[0];
        // Don't add if already exists
        if (poolEl.querySelector('.pool-search-wrap')) return;

        const searchWrap = document.createElement('div');
        searchWrap.className = 'pool-search-wrap';
        searchWrap.innerHTML = `
            <div style="position:relative">
                <input type="text" class="pool-search" placeholder="Хайх... (нэр, нас, жин)">
                <button class="pool-search-clear">&times;</button>
            </div>
        `;
        poolEl.insertBefore(searchWrap, poolEl.firstChild);

        const input = searchWrap.querySelector('.pool-search');
        const clearBtn = searchWrap.querySelector('.pool-search-clear');

        input.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            clearBtn.classList.toggle('visible', query.length > 0);
            filterPool(query);
        });

        clearBtn.addEventListener('click', function () {
            input.value = '';
            clearBtn.classList.remove('visible');
            filterPool('');
            input.focus();
        });
    }

    function filterPool(query) {
        const poolEl = $pool[0];

        // Show/hide bracket nodes
        poolEl.querySelectorAll('.tree-node').forEach(node => {
            if (!query) {
                node.classList.remove('search-hidden');
            } else {
                const text = node.textContent.toLowerCase();
                node.classList.toggle('search-hidden', !text.includes(query));
            }
        });

        // Show/hide category groups that have no visible children
        poolEl.querySelectorAll('.pool-group').forEach(group => {
            const hasVisible = group.querySelector('.tree-node:not(.search-hidden)');
            group.classList.toggle('pool-group-hidden', !hasVisible && !!query);
        });
    }

    // ─── Collapsible Pool Categories ─────────────────────
    function setupCategoryCollapse() {
        $pool[0].querySelectorAll('.pool-category').forEach(cat => {
            // Add collapse icon if not present
            if (!cat.querySelector('.collapse-icon')) {
                const icon = document.createElement('i');
                icon.className = 'la la-angle-down collapse-icon';
                cat.insertBefore(icon, cat.firstChild);
            }

            // Remove old listener by cloning
            const newCat = cat.cloneNode(true);
            cat.parentNode.replaceChild(newCat, cat);

            newCat.addEventListener('click', function () {
                this.classList.toggle('collapsed');
                // Find the next sibling pool-group (the content)
                const content = this.nextElementSibling;
                if (content && content.classList.contains('pool-group')) {
                    content.classList.toggle('collapsed-content');
                }
            });
        });
    }

    // ─── Mat / Schedule Rendering ────────────────────────
    function renderSchedule() {
        $schedule.empty();

        state.schedule.forEach(day => {
            const dayLabel = document.createElement('div');
            dayLabel.className = 'day-label';
            dayLabel.innerHTML = `<i class="la la-calendar me-1"></i>Day ${day.day}`;

            const scrollWrapper = document.createElement('div');
            scrollWrapper.className = 'mat-slider-wrapper mb-4 p-3';

            const scrollContent = document.createElement('div');
            scrollContent.className = 'mat-slider';

            day.mats.forEach(mat => {
                const key = `${day.day_id}_${mat.mat_id}`;
                const col = document.createElement('div');
                col.className = 'col-md-5 mat-col mb-4';

                const drop = document.createElement('div');
                drop.className = 'dropzone p-2 border rounded mate-poll';
                drop.dataset.day = day.day_id;
                drop.dataset.mat = mat.mat_id;
                drop.dataset.key = key;

                // Mat header with count and clear button
                const header = document.createElement('div');
                header.className = 'mat-header';
                header.innerHTML = `
                    <h6>Mat ${mat.mat}</h6>
                    <div class="mat-header-right">
                        <span class="mat-count" data-mat-key="${key}">0</span>
                        <button class="mat-clear-btn d-none" data-mat-key="${key}" title="Бүх хаалтыг буцаах">
                            <i class="la la-trash-alt"></i> Цэвэрлэх
                        </button>
                    </div>
                `;
                drop.appendChild(header);

                // Build assignments
                state.matAssignments[key] = mat.brackets.map(b => bracketKey(b));

                // Render assigned brackets
                const assignedBrackets = state.matAssignments[key].map(bKey => {
                    const bracket = mat.brackets.find(m => bracketKey(m) === bKey);
                    const pointer = state.shortcutMap[bKey];
                    if (pointer && bracket) {
                        pointer.is_complete = bracket.is_complete || false;
                    }
                    return pointer;
                }).filter(Boolean);

                renderBracketsInto(drop, assignedBrackets);

                // Track locations
                mat.brackets.forEach(b => {
                    state.bracketLocations[bracketKey(b)] = key;
                });

                // Empty hint
                if (assignedBrackets.length === 0) {
                    const hint = document.createElement('div');
                    hint.className = 'dropzone-empty-hint';
                    hint.textContent = 'Хаалтуудыг энд чирнэ үү';
                    drop.appendChild(hint);
                }

                col.appendChild(drop);
                scrollContent.appendChild(col);
            });

            scrollWrapper.appendChild(scrollContent);
            $schedule.append(dayLabel);
            $schedule.append(scrollWrapper);
        });

        updateMatCounts();
    }

    function updateMatCounts() {
        Object.keys(state.matAssignments).forEach(key => {
            const brackets = state.matAssignments[key];
            const count = brackets.length;

            // Count total athletes across assigned brackets
            let totalAthletes = 0;
            brackets.forEach(bKey => {
                const data = state.shortcutMap[bKey];
                if (data && data.total) totalAthletes += data.total;
            });

            const $counter = $(`.mat-count[data-mat-key="${key}"]`);
            const suffix = totalAthletes > 0 ? ` (${totalAthletes} тамирчин)` : '';
            $counter.text(`${count} хаалт${suffix}`);

            // Show/hide clear button — hide if all brackets are locked or none assigned
            const $clearBtn = $(`.mat-clear-btn[data-mat-key="${key}"]`);
            const hasUnlocked = brackets.some(bKey => !state.lockedBrackets.has(bKey));
            if (count > 0 && hasUnlocked) {
                $clearBtn.removeClass('d-none');
            } else {
                $clearBtn.addClass('d-none');
            }
        });
    }

    // ─── Drag & Drop ─────────────────────────────────────
    function removeFromMat(bKey) {
        const matKey = state.bracketLocations[bKey];
        if (matKey && state.matAssignments[matKey]) {
            const idx = state.matAssignments[matKey].indexOf(bKey);
            if (idx > -1) {
                state.matAssignments[matKey].splice(idx, 1);
            }
        }
        delete state.bracketLocations[bKey];
    }

    // Find which bracket to insert before based on mouse Y position
    function getInsertBeforeElement(container, y) {
        const siblings = [...container.querySelectorAll('.tree-node:not(.dragging)')];
        let closest = null;
        let closestOffset = Number.POSITIVE_INFINITY;

        siblings.forEach(child => {
            const box = child.getBoundingClientRect();
            const midY = box.top + box.height / 2;
            const offset = y - midY;
            // We want the first element whose midpoint is BELOW the cursor
            if (offset < 0 && Math.abs(offset) < closestOffset) {
                closestOffset = Math.abs(offset);
                closest = child;
            }
        });
        return closest; // null means insert at end
    }

    function clearDropIndicators() {
        document.querySelectorAll('.drop-above, .drop-below').forEach(el => {
            el.classList.remove('drop-above', 'drop-below');
        });
    }

    function setupDragAndDrop() {
        // Draggable brackets
        document.querySelectorAll('.tree-node').forEach(node => {
            node.addEventListener('dragstart', e => {
                const bKey = node.dataset.id;
                if (state.lockedBrackets.has(bKey)) {
                    e.preventDefault();
                    return;
                }
                e.dataTransfer.setData('bracketKey', bKey);
                e.dataTransfer.effectAllowed = 'move';
                node.classList.add('dragging');

                // Highlight all valid drop zones
                document.querySelectorAll('.dropzone').forEach(z => {
                    z.style.borderColor = '#6366f1';
                });
            });

            node.addEventListener('dragend', () => {
                node.classList.remove('dragging');
                clearDropIndicators();
                // Remove all highlights
                document.querySelectorAll('.dropzone').forEach(z => {
                    z.style.borderColor = '';
                    z.classList.remove('drag-over');
                });
            });
        });

        // Drop zones
        document.querySelectorAll('.dropzone').forEach(zone => {
            zone.addEventListener('dragover', e => {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                zone.classList.add('drag-over');

                // Show insert position indicator (only for mat zones, not pool)
                if (zone.id !== 'bracket-pool') {
                    clearDropIndicators();
                    const insertBefore = getInsertBeforeElement(zone, e.clientY);
                    if (insertBefore) {
                        insertBefore.classList.add('drop-above');
                    } else {
                        // Inserting at end — show indicator on last element
                        const last = zone.querySelector('.tree-node:last-of-type');
                        if (last) last.classList.add('drop-below');
                    }
                }
            });

            zone.addEventListener('dragleave', e => {
                // Only remove if actually leaving the zone (not entering a child)
                if (!zone.contains(e.relatedTarget)) {
                    zone.classList.remove('drag-over');
                    clearDropIndicators();
                }
            });

            zone.addEventListener('drop', e => {
                e.preventDefault();
                zone.classList.remove('drag-over');
                clearDropIndicators();

                const bKey = e.dataTransfer.getData('bracketKey');
                if (!bKey || state.lockedBrackets.has(bKey)) return;

                const bracketNode = document.querySelector(`[data-id="${bKey}"]`);
                if (!bracketNode) return;

                const isPool = zone.id === 'bracket-pool';

                if (isPool) {
                    // Return to pool
                    removeFromMat(bKey);
                    bracketNode.remove();
                    // Re-render pool to restore tree structure
                    $pool.empty();
                    renderPoolTree($pool[0], state.bracketPool);
                    setupDragAndDrop();
                } else {
                    const matKey = `${zone.dataset.day}_${zone.dataset.mat}`;

                    // Remove from previous location (if any)
                    removeFromMat(bKey);

                    // Find insert position
                    const insertBefore = getInsertBeforeElement(zone, e.clientY);

                    if (!state.matAssignments[matKey]) {
                        state.matAssignments[matKey] = [];
                    }

                    if (insertBefore) {
                        // Insert before this element in DOM
                        zone.insertBefore(bracketNode, insertBefore);
                        // Insert in array at correct position
                        const beforeKey = insertBefore.dataset.id;
                        const idx = state.matAssignments[matKey].indexOf(beforeKey);
                        if (idx > -1) {
                            state.matAssignments[matKey].splice(idx, 0, bKey);
                        } else {
                            state.matAssignments[matKey].push(bKey);
                        }
                    } else {
                        // Append at end
                        zone.appendChild(bracketNode);
                        // Prevent duplicates
                        if (!state.matAssignments[matKey].includes(bKey)) {
                            state.matAssignments[matKey].push(bKey);
                        }
                    }

                    state.bracketLocations[bKey] = matKey;

                    // Remove empty hint if present
                    const hint = zone.querySelector('.dropzone-empty-hint');
                    if (hint) hint.remove();
                }

                markDirty();
                updateMatCounts();
                updatePoolCount();
            });
        });
    }

    // ─── Schedule Conversion ─────────────────────────────
    function convertToSchedulePayload() {
        const result = [];

        for (const key in state.matAssignments) {
            const [dayId, matId] = key.split('_').map(Number);

            let dayObj = result.find(d => d.day === dayId);
            if (!dayObj) {
                dayObj = { day: dayId, mats: [] };
                result.push(dayObj);
            }

            dayObj.mats.push({
                mat: matId,
                brackets: state.matAssignments[key].map(parseBracketKey),
            });
        }

        // Sort for consistency
        result.sort((a, b) => a.day - b.day);
        result.forEach(d => d.mats.sort((a, b) => a.mat - b.mat));

        return result;
    }

    // ─── Loading Overlay ─────────────────────────────────
    function showLoading(msg) {
        $('body').append(`
            <div class="saving-overlay" id="loading-overlay">
                <div class="text-center">
                    <div class="spinner-border text-primary mb-2" role="status"></div>
                    <div class="fw-bold">${msg || 'Processing...'}</div>
                </div>
            </div>
        `);
    }

    function hideLoading() {
        $('#loading-overlay').remove();
    }

    // ─── Main Render ─────────────────────────────────────
    function render() {
        state.bracketPool = @json($bracketPool) || [];
        state.schedule    = @json($mateData) || [];
        state.shortcutMap = extractBracketsFromPool(state.bracketPool);

        // Reset state
        state.matAssignments = {};
        state.bracketLocations = {};
        state.lockedBrackets.clear();

        markClean();

        $pool.empty();
        $schedule.empty();

        renderSchedule();
        renderPoolTree($pool[0], state.bracketPool);
        setupPoolSearch();
        setupCategoryCollapse();
        setupDragAndDrop();
        updatePoolCount();
    }

    // ─── Event Handlers ──────────────────────────────────

    // Mat clear button — use event delegation on schedule container
    $schedule.on('click', '.mat-clear-btn', function (e) {
        e.preventDefault();
        e.stopPropagation();
        const matKey = $(this).data('mat-key');
        if (!matKey) return;

        const count = (state.matAssignments[matKey] || [])
            .filter(bKey => !state.lockedBrackets.has(bKey)).length;

        if (count === 0) return;

        Swal.fire({
            title: `Mat-н ${count} хаалтыг буцаах уу?`,
            text: 'Бүх unlock хаалтууд Pool руу буцна.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Тийм, цэвэрлэх',
            cancelButtonText: 'Үгүй',
            customClass: {
                confirmButton: 'btn btn-danger btn-sm',
                cancelButton: 'btn btn-secondary btn-sm',
            },
        }).then(function (result) {
            if (result.value) {
                clearMat(matKey);
            }
        });
    });
    $saveBtn.on('click', function () {
        const schedule = convertToSchedulePayload();
        const eventId = @json($eventConfig['event_id']);

        showLoading('Saving...');

        $.ajax({
            url: '{{ route('event.config.saveMateBracket', ['event_id' => ':event_id']) }}'.replace(':event_id', eventId),
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                schedule: schedule,
            },
            success: function (response) {
                hideLoading();
                if (response.status === 'success') {
                    markClean();
                    state.originalSchedule = JSON.parse(JSON.stringify(state.schedule));
                    $('#config_tabs').find('li a.active').trigger('click');
                    toastr.success(response.message);
                } else {
                    toastr.error('Failed to save brackets.');
                }
            },
            error: function (xhr) {
                hideLoading();
                var msg = 'An error occurred while saving.';
                try {
                    var resp = JSON.parse(xhr.responseText);
                    if (resp.message) msg = resp.message;
                } catch (e) { /* ignore parse error */ }
                console.error('Save error:', msg);
                toastr.error(msg);
            },
        });
    });

    $cancelBtn.on('click', function () {
        if (state.originalSchedule) {
            state.schedule = JSON.parse(JSON.stringify(state.originalSchedule));
            state.matAssignments = {};
            state.bracketLocations = {};
            state.lockedBrackets.clear();

            markClean();
            $pool.empty();
            $schedule.empty();

            renderSchedule();
            renderPoolTree($pool[0], state.bracketPool);
            setupPoolSearch();
            setupCategoryCollapse();
            setupDragAndDrop();
            updatePoolCount();
        }
    });

    $generateBtn.on('click', function () {
        var eventId = @json($eventConfig['event_id']);
        $.get('{!! route('event.config.match.index') !!}/' + eventId, function (data) {
            var $modal = $('#eventEntryModal');
            $modal.modal();

            $modal.one('shown.bs.modal', function () {
                $modal.find('.modal-content').html(data);
                $('.selectpicker').selectpicker();

                var datepickerDefaults = {
                    rtl: KTUtil.isRTL(),
                    todayHighlight: true,
                    orientation: 'bottom left',
                    format: 'yyyy-mm-dd',
                    templates: {
                        leftArrow: '<i class="la la-angle-right"></i>',
                        rightArrow: '<i class="la la-angle-left"></i>',
                    },
                };

                $('#start_date').datepicker(datepickerDefaults);
                $('#end_date').datepicker(datepickerDefaults);

                $('#event-config-days-entries-form').validate({
                    rules: {},
                    messages: {},
                    submitHandler: function (form) {
                        Swal.fire({
                            title: 'Та шинэ дэвсгэр үүсгэхийг хүсэж байна уу? Энэ нь одоо байгаа БҮХ тохирол, хаалт болон өдрийн оруулгуудыг дахин тохируулна.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Тийм',
                            cancelButtonText: 'Үгүй',
                            customClass: {
                                confirmButton: 'btn btn-primary',
                                cancelButton: 'btn btn-secondary',
                            },
                        }).then(function (result) {
                            if (result.value) {
                                $.ajax({
                                    url: form.action,
                                    type: form.method,
                                    data: new FormData(form),
                                    processData: false,
                                    contentType: false,
                                    success: function (response) {
                                        if (response.status === 'success') {
                                            $('#eventConfigModal').find('#close').trigger('click');
                                            $('#config_tabs').find('li a.active').trigger('click');
                                            toastr.success(response.msg);
                                        } else {
                                            toastr.error(response.errors, response.msg, {
                                                closeButton: true,
                                                timeOut: '0',
                                                extendedTimeOut: '0',
                                            });
                                        }
                                    },
                                    error: function (xhr, textStatus, error) {
                                        console.error('Generate error:', textStatus, error);
                                        toastr.error('An error occurred.');
                                    },
                                });
                            }
                        });
                    },
                });
            });

            $modal.one('hidden.bs.modal', function () {
                $modal.find('.modal-content').empty();
            });
        });
    });

    // ─── Initialize ──────────────────────────────────────
    render();
    state.originalSchedule = JSON.parse(JSON.stringify(state.schedule));
});
</script>