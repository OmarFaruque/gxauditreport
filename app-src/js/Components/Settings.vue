<template>
    <div>
        <el-container>
            <el-header>
                <el-row>
                    <h1>Settings</h1>
                </el-row>
            </el-header>
            <el-main>
                <el-row class="mb-3">
                    <el-col :span="12">
                        <el-card shadow="always">
                            <h4>Shortcode in php</h4>
                            <code>&lt;?php echo do_shortcode('[gx_score_board]'); ?&gt;</code>
                        </el-card>
                    </el-col>
                </el-row>
                <el-row>
                    <el-col :span="12">
                        <el-card shadow="always">
                            <h4>Shortcode in content</h4>
                            <code>[gx_score_board]</code>
                        </el-card>
                    </el-col>
                </el-row>
            </el-main>
      </el-container>
    </div>
</template>
<script>
export default {
    name: 'Settings'
}
</script>

